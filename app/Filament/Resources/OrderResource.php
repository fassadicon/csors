<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use App\Models\Event;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Package;
use App\Models\Utility;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use App\Enums\PaymentStatus;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Actions\Action;
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

    // See
    // https://laraveldaily.com/post/filament-repeater-live-calculations-on-update/

    // Repeater Component -  // Disable options that are already selected in other rows
    // ->disableOptionWhen(function ($value, $state, Get $get) {
    //     return collect($get('../*.product_id'))
    //         ->reject(fn($id) => $id == $state)
    //         ->filter()
    //         ->contains($value);
    // })

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make([
                Forms\Components\Select::make('user_id')
                    ->preload()
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start')
                    ->date()
                    ->beforeOrEqual('end')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->date()
                    ->afterOrEqual('start')
                    ->required(),
                Forms\Components\Select::make('caterer_id')
                    ->preload()
                    ->relationship('caterer', 'name')
                    ->default(auth()->user()->caterer->id)
                    ->hidden(auth()->user()->hasRole('caterer'))
                    ->required(),
                Forms\Components\Textarea::make('remarks')
                    ->nullable()
                    ->columnSpan(fn() => auth()->user()->hasRole('superadmin') ? 2 : 3),
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
                            ->searchable()
                            ->types([
                                MorphToSelect\Type::make(Utility::class)
                                    ->getOptionLabelFromRecordUsing(fn(Utility $record): string => "$record->name - (₱$record->price/[pc/set])"),
                                MorphToSelect\Type::make(Package::class)
                                    ->getOptionLabelFromRecordUsing(fn(Package $record): string => "$record->name - (₱$record->price/pax)"),
                                MorphToSelect\Type::make(Food::class)
                                    ->getOptionLabelFromRecordUsing(fn(Food $record): string =>
                                    $record->foodDetail->name .  " - " . $record->servingType->name . " (₱" . $record->price . "/pax)"),
                            ])
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $set('amount', static::getAmount($state['orderable_type'], $state['orderable_id'], $get('quantity')));

                                $totalAmount = static::getTotalAmount($get('../'));
                                $deductedAmount = static::getDeductedAmount($get('../../promo_id'), $totalAmount);
                                $set('../../deducted_amount', $deductedAmount);
                                $set('../../total_amount', $totalAmount - $deductedAmount);
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
                    }),
                Forms\Components\TextInput::make('deducted_amount')
                    ->live(debounce: 500)
                    ->readOnly()
                    ->default(0)
                    ->prefix('- ₱')
                    ->numeric()
                    ->hidden(fn($get) => $get('promo_id') === null),
                Forms\Components\TextInput::make('total_amount')
                    ->readOnly()
                    ->default(0)
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->live(debounce: 500),
                Forms\Components\Select::make('order_status')
                    ->default(OrderStatus::Pending)
                    ->options(OrderStatus::class)
                    ->required(),
            ])
                ->columns(2),
            Forms\Components\Section::make([
                Forms\Components\Select::make('payment_status')
                    ->default(PaymentStatus::Pending)
                    ->options(PaymentStatus::class)
                    ->live()
                    ->required(),
                Forms\Components\Repeater::make('payments')
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
                                'manual' => 'Manual',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('method')
                            ->label('Method'),
                        Forms\Components\TextInput::make('reference_no')
                            ->label('Reference Number')
                            ->columnSpan(2),
                    ])
                    ->reorderable()
                    ->deleteAction(
                        fn(Action $action) => $action->requiresConfirmation(),
                    )
                    ->columns(6)
            ])
                ->hidden(fn($get) => $get('total_amount') <= 0),

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
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('created_at', '>=', today())
            ->count();
    }
}
