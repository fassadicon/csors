<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Food;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Package;
use App\Models\Utility;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Filament\Notifications;
use App\Enums\PaymentStatus;
use App\Models\ReportedUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Modal;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Select;
use App\Filament\Exports\OrderExporter;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CancellationRequestStatus;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static $reportReasons = [
        "Abusive behavior" => "Abusive behavior towards staff or other users.",
        "Fraudulent activity" => "Fraudulent activity, such as using stolen credit cards.",
        "Repeated cancellations" => "Repeated cancellations without valid reasons.",
        "Failure to pay" => "Failure to pay for services rendered.",
        "Inappropriate requests" => "Inappropriate requests that violate company policies."
    ];


    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make([
                Forms\Components\Select::make('user_id')
                    ->preload()
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('is_customer', 1)
                    )
                    ->visibleOn('create')
                    ->required(),
                Forms\Components\TextInput::make('recipient')
                    ->required(),
                Forms\Components\Textarea::make('location')
                    ->required(),
                Forms\Components\DateTimePicker::make('start')
                    ->native(false)
                    ->disabledDates(function () {
                        if (auth()->user()->hasRole('caterer')) {
                            return auth()->user()->caterer->disabledDates
                                ->pluck('date')
                                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                                ->toArray();
                        }
                        return null;
                    })
                    ->date()
                    ->beforeOrEqual('end')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->native(false)
                    ->disabledDates(function () {
                        if (auth()->user()->hasRole('caterer')) {
                            return auth()->user()->caterer->disabledDates
                                ->pluck('date')
                                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                                ->toArray();
                        }
                        return null;
                    })
                    ->date()
                    ->afterOrEqual('start')
                    ->required(),
                Forms\Components\Select::make('caterer_id')
                    ->preload()
                    ->relationship('caterer', 'name')
                    ->hidden(auth()->user()->hasRole('caterer'))
                    ->required(),

                Forms\Components\Textarea::make('remarks')
                    ->nullable(),
            ])
                ->columns(3),
            Forms\Components\Section::make([
                Forms\Components\Repeater::make('orderItems')
                    ->label('Order List')
                    ->relationship()
                    ->schema([
                        Forms\Components\MorphToSelect::make('orderable')
                            ->label('Order Item')
                            ->preload()
                            ->types([
                                MorphToSelect\Type::make(Utility::class)
                                    ->modifyOptionsQueryUsing(fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                                        $query->where('caterer_id', auth()->user()->caterer->id);
                                    }))
                                    ->getOptionLabelFromRecordUsing(fn(Utility $record): string => "$record->name - (₱$record->price/[pc/set])"),
                                MorphToSelect\Type::make(Package::class)
                                    ->modifyOptionsQueryUsing(fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                                        $query->whereHas('events', function ($query) {
                                            $query->where('caterer_id', auth()->user()->caterer->id);
                                        });
                                    }))
                                    ->getOptionLabelFromRecordUsing(fn(Package $record): string => "$record->name - (₱$record->price/pax)"),
                                MorphToSelect\Type::make(Food::class)
                                    ->modifyOptionsQueryUsing(fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                                        $query->whereHas('servingType', function ($query) {
                                            $query->where('caterer_id', auth()->user()->caterer->id);
                                        });
                                    }))
                                    ->getOptionLabelFromRecordUsing(fn(Food $record): string =>
                                    $record->foodDetail->name .  " - " . $record->servingType->name . " (₱" . $record->price . "/pax)"),
                            ])
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $set('amount', static::getAmount($state['orderable_type'], $state['orderable_id'], $get('quantity')));

                                $totalAmount = static::getTotalAmount($get('../'));
                                $deductedAmount = static::getDeductedAmount($get('../../promo_id'), $totalAmount);
                                $set('../../deducted_amount', $deductedAmount);
                                $set('../../total_amount', $totalAmount - $deductedAmount);
                                // $set('../../vat', $totalAmount * 0.12);
                                $set('../../final_amount', (($totalAmount - $deductedAmount) + (($totalAmount - $deductedAmount) * 0.12)) + $get('../../delivery_amount'));
                            })
                            ->live(debounce: 500)
                            ->required()
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('quantity')
                            ->minValue(1)
                            ->live(debounce: 500)
                            ->default(25)
                            ->integer()
                            ->required()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $set('amount', static::getAmount($get('orderable_type'), $get('orderable_id'), $state));

                                $totalAmount = static::getTotalAmount($get('../'));
                                $deductedAmount = static::getDeductedAmount($get('../../promo_id'), $totalAmount);
                                $set('../../deducted_amount', $deductedAmount);
                                $set('../../total_amount', $totalAmount - $deductedAmount);
                                // $set('../../vat', $totalAmount * 0.12);
                                $set('../../final_amount', ((($totalAmount - $deductedAmount)) + (($totalAmount - $deductedAmount) * 0.12)) + $get('../../delivery_amount'));
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('amount')
                            ->prefix('₱')
                            ->live()
                            ->readOnly()
                            ->required()
                            ->columnSpan(4),
                    ])
                    ->afterStateUpdated(function ($get, $set) {
                        $totalAmount = static::getTotalAmount($get('orderItems'));
                        $deductedAmount = static::getDeductedAmount($get('promo_id'), $totalAmount);
                        $set('deducted_amount', $deductedAmount);
                        $set('total_amount', $totalAmount - $deductedAmount);
                        // $set('vat', $totalAmount * 0.12);
                        $set('final_amount', ((($totalAmount - $deductedAmount)) + (($totalAmount - $deductedAmount) * 0.12)) + $get('delivery_amount'));
                    })
                    ->reorderable()
                    ->columns(12)
            ]),
            Forms\Components\Section::make([
                Forms\Components\Select::make('promo_id')
                    ->preload()
                    ->relationship(
                        name: 'promo',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('minimum', '<=', $get('total_amount')),
                    )
                    ->nullable()
                    ->live()
                    ->hidden(fn($get) => $get('total_amount') <= 0)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        $set('deducted_amount', static::getDeductedAmount($state, static::getTotalAmount($get('orderItems'))));
                        $set('total_amount', static::getTotalAmount($get('orderItems')) - $get('deducted_amount'));
                        // $set('vat', $get('total_amount') * 0.12);
                        $set('final_amount', ($get('total_amount') + ($get('total_amount') * 0.12)) + $get('delivery_amount'));
                    }),
                Forms\Components\TextInput::make('deducted_amount')
                    ->live(debounce: 500)
                    ->readOnly()
                    ->default(0)
                    ->prefix('- ₱')
                    ->numeric()
                    ->hidden(fn($get) => $get('promo_id') == null || $get('promo_id') == ''),
                Forms\Components\TextInput::make('delivery_amount')
                    ->label('Delivery Fee')
                    ->readOnly(function ($get) {
                        return in_array($get('order_status'), ['completed', 'declined', 'cancelled']);
                    })
                    ->default(0.00)
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        $set('final_amount', floatval(($get('total_amount') + ($get('total_amount') * 0.12)) + $state));
                    }),
                Forms\Components\TextInput::make('total_amount')
                    ->label('Subtotal')
                    ->readOnly()
                    ->default(0)
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500),
                // Forms\Components\TextInput::make('vat')
                //     ->label('VAT')
                //     ->live()
                //     ->prefix('₱')
                //     ->numeric()
                //     ->default(fn(Get $get) => $get('total_amount') * 0.12)
                //     ->readOnly(),
                Forms\Components\TextInput::make('final_amount')
                    ->label('Total (Subtotal + 12% VAT + Delivery Fee)')
                    ->live(debounce: 500)
                    ->numeric()
                    ->prefix('₱')
                    ->readOnly()
                    ->required(),
                Forms\Components\Select::make('order_status')
                    ->default(OrderStatus::Pending)
                    ->options(OrderStatus::class)
                    ->live()
                    ->visibleOn('edit')
                    ->disableOptionWhen(function (string $value, Model $record) {
                        if ($record->order_status === OrderStatus::Completed) {
                            return in_array($value, ['pending', 'confirmed', 'cancelled', 'declined']);
                        }

                        if ($record->order_status === OrderStatus::Confirmed) {
                            return in_array($value, ['pending', 'declined']);
                        }

                        if ($record->order_status === OrderStatus::Cancelled) {
                            return in_array($value, ['pending', 'confirmed', 'completed', 'declined']);
                        }

                        if ($record->order_status === OrderStatus::Pending) {
                            return in_array($value, ['completed', 'cancelled']);
                        }

                        if ($record->order_status === OrderStatus::Declined) {
                            return in_array($value, ['pending', 'confirmed', 'completed', 'cancelled']);
                        }

                        return false;
                    })
                    ->afterStateUpdated(function ($state, $get, $set) {
                        if ($state == 'pending') {
                            $set('delivery_amount', 0.00);
                        }
                        $set('final_amount', floatval(($get('total_amount') + $get('total_amount') * 0.12) + $get('delivery_amount')));
                    })
                    ->required(),
                Forms\Components\Textarea::make('decline_reason')
                    ->live()
                    ->visible(fn(Get $get) => $get('order_status') == 'declined')
                    ->nullable()
            ])
                ->columns(2),
            Forms\Components\Section::make([
                Forms\Components\Select::make('payment_status')
                    ->options(PaymentStatus::class)
                    ->default(PaymentStatus::Pending)
                    ->live()
                    ->required()
                    ->disableOptionWhen(function (string $value, Model $record) {
                        // If the current status is 'paid', disable all other options
                        if ($record->payment_status === PaymentStatus::Paid) {
                            return $value !== 'paid';
                        }

                        // If the current status is 'partial', disable all options except 'partial' and 'paid'
                        if ($record->payment_status === PaymentStatus::Partial) {
                            return !in_array($value, ['partial', 'paid']);
                        }

                        // No options are disabled in other cases
                        return false;
                    }),
                Forms\Components\Repeater::make('payments')
                    ->disabled(function ($get) {
                        return $get('payment_status') == 'paid';
                    })
                    ->hidden(function ($get) {
                        return $get('payment_status') === PaymentStatus::Pending;
                    })
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Payment Amount')
                            ->prefix('₱')
                            ->columnSpan(2),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'cash' => 'Cash',
                                'online' => 'Online',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('method')
                            ->label('Method'),
                        Forms\Components\TextInput::make('reference_no')
                            ->label('Reference Number')
                            ->columnSpan(2),
                    ])
                    ->reorderable()
                    // ->deleteAction(
                    //     fn(Action $action) => $action->requiresConfirmation(),
                    // )
                    ->columns(6)
            ])
                ->visibleOn('edit'),
            Forms\Components\Section::make([
                Forms\Components\Group::make()
                    ->label('Cancellation Request')
                    ->relationship('cancellationRequest')
                    ->live()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Cancellation Status')
                            ->options(CancellationRequestStatus::class)
                            ->required(),
                        // ->required(fn(?Model $record) => dd($record)),
                        Forms\Components\Textarea::make('reason')
                            // ->readOnlyOn('edit')
                            ->required(),
                        Forms\Components\Textarea::make('response')
                            ->required(),
                    ])
                    ->columns(3),
            ])
                ->visible(fn(?Model $record, Get $get) => ($record && $record->cancellationRequest != null) || $get('order_status') == 'cancelled')
            // ->visible(fn(?Model $record, $livewire) => $record->cancellationRequest && $livewire instanceof \Filament\Resources\Pages\EditRecord)
            // ->visible(fn($record) => $record->cancellationRequest !== null)
        ];
    }

    /* Order Form Custom Functions */

    protected static function getTotalAmount($orderItems = null): float
    {
        if ($orderItems === null) {
            return 0;
        }

        $totalAmount = 0;
        foreach ($orderItems as $orderItem) {
            $totalAmount += $orderItem['amount'];
        }

        // Add code to subtract delivery fee (if any) to total amount

        return $totalAmount;
    }

    protected static function getDeductedAmount(int $promoId = null, float $totalAmount): float
    {
        if ($promoId === null) {
            return 0;
        }

        $promo = Promo::find($promoId);

        return $promo->type === 'percentage' ? ($promo->value / 100) * $totalAmount : $promo->value;
    }

    protected static function getAmount($orderableType, $orderableId, $quantity = 1): float
    {
        $amount = 0;

        if ($orderableType !== null && $orderableId !== null) {
            if ($orderableType === 'App\Models\Utility') {
                $amount = Utility::where('id', $orderableId)->pluck('price')->first();
            } else if ($orderableType === 'App\Models\Package') {
                $amount = Package::where('id', $orderableId)->pluck('price')->first();
            } else if ($orderableType === 'App\Models\Food') {
                $amount = Food::where('id', $orderableId)->pluck('price')->first();
            }

            $amount = floatval($amount) * floatval($quantity);
        }

        return $amount;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(OrderExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->formatStateUsing(function ($state, Model $record) {
                        return $state == $record->recipient ? $record->recipient . ' (' . $state . ')' : $state;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderItems')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->formatStateUsing(function ($state) {
                        $orderable_type = get_class($state->orderable);
                        if ($orderable_type === 'App\Models\Food') {
                            return $state->orderable->foodDetail->name . ' - ' . $state->orderable->servingType->name . ' (' . $state->quantity . ' pax) - ₱' . $state->amount;
                        }

                        return $state->orderable->name . ' (' . $state->quantity . ' set/pcs) - ₱' . $state->amount;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->sortable()
                    ->dateTime('M j, Y g:i A')
                    ->wrap(),
                Tables\Columns\TextColumn::make('end')
                    ->sortable()
                    ->dateTime('M j, Y g:i A')
                    ->wrap(),
                Tables\Columns\TextColumn::make('caterer.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('order_status')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('promo_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // INSERT PAYMENTS HERE
                // Tables\Columns\TextColumn::make('payment_id')
                //     ->numeric()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deducted_amount')
                    ->money('php')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Subtotal')
                    ->numeric()
                    ->money('php')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Total')
                    ->numeric()
                    ->money('php')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->multiple()
                    ->options(PaymentStatus::class),
                Tables\Filters\SelectFilter::make('order_status')
                    ->multiple()
                    ->options(OrderStatus::class),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

                ActionGroup::make([


                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),

                    // VIEW RECEITPT
                    Action::make('orderItems')->label('View Receipt')
                        ->icon('heroicon-m-receipt-percent')
                        ->modalHeading('Receipt Details')
                        ->modalContent(function ($record) {
                            // Fetch only the order items related to this specific order
                            $items = $record->orderItems; // Assuming $record is an instance of your order model
                            $payments = $record->payments; // Assuming $record is an instance of your order model
                            // Return the view with the order items
                            return view('filament.order.receipt', [
                                'order' => $items,
                                'payments' => $payments
                            ]);
                        })->modalSubmitAction(false)->modalCancelActionLabel('Close'),
                    Action::make('downloadReceipt')->label('Download Receipt')
                        ->visible(fn($record) => $record->payment_status->value != 'pending')
                        ->icon('heroicon-m-printer')
                        ->action(function ($record) {
                            $order = $record->load([
                                'caterer',
                                'orderItems',
                                'payments',
                                'orderItems',
                                'user',
                            ]);

                            $pdf  = Pdf::setOption([
                                'isRemoteEnabled' => true,
                            ])
                                ->setPaper('a4', 'portrait')
                                ->loadHtml(view('pdf.receipt', [
                                    'order' => $order
                                ])->render());

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->stream();
                            }, 'receipt.pdf');
                        }),

                    Action::make('user_id')->label('Report Customer')
                        ->icon('heroicon-m-flag')
                        ->form([
                            Select::make('comment') // Ensure this is the correct key for the selected reason
                                ->label('Reason')
                                ->options(self::$reportReasons)
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            // Create the reported user record
                            ReportedUser::create([
                                'user_id' => auth()->user()->id, // Assuming this is the reporter's user ID
                                'reported_user' => $record->user_id, // Assuming this is the user being reported
                                'comment' => $data['comment'], // Capture the selected reason
                            ]);

                            // Add a success notification
                            Notification::make()
                                ->title('Report Submitted')
                                ->body('The report has been successfully submitted.')
                                ->success() // You can also use error() for error notifications
                                ->send();
                        })
                        ->visible(function ($record) {
                            // Check if the user has already reported this customer
                            return !ReportedUser::where('user_id', auth()->user()->id)
                                ->where('reported_user', $record->user_id)
                                ->exists();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // FOR REPORTING
    // Method to open the modal
    protected function openReportModal(Order $order)
    {
        $this->modalData['order'] = $order; // Store order data in a property

        // Show the modal
        $this->dispatch('open-modal');
    }

    // Define the modal
    public function getModalContent(): array
    {
        return [
            Modal::make('Report User')
                ->form([
                    Textarea::make('comment')
                        ->label('Comment')
                        ->required(),
                    Button::make('Submit')
                        ->action('submitReport')
                        ->color('primary'),
                ])
                ->action('submitReport'),
        ];
    }



    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->where('caterer_id', auth()->user()->caterer->id);
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::when(auth()->user()->hasRole('caterer'), function ($query) {
            $query->where('caterer_id', auth()->user()->caterer->id);
        })
            ->where(function ($query) {
                $query->where('order_status', 'pending')
                    ->orWhere('order_status', 'confirmed');
            })
            ->count();
    }
}
