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
use Filament\Forms\Form;
use Filament\Tables\Table;
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

    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationLabel = 'List';
    // See
    // https://laraveldaily.com/post/filament-repeater-live-calculations-on-update/

    public static function getFormSchema() : array {
        return [
            Forms\Components\Section::make([
                Forms\Components\Select::make('user_id')
                    ->preload()
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->afterOrEqual('start')
                    ->required(),
                Forms\Components\Select::make('caterer_id')
                    ->preload()
                    ->relationship('caterer', 'name')
                    ->required(),
                Forms\Components\Textarea::make('remarks')
                    ->nullable()
                    ->columnSpan(fn() => auth()->user()->caterer ? 2 : 3),
            ])
                ->columns(3),
            Forms\Components\Section::make([
                Forms\Components\Repeater::make('orderItems')
                    ->label('Order List')
                    ->relationship()
                    ->schema([
                        Forms\Components\MorphToSelect::make('orderable')
                            ->label('Order Item')
                            ->live()
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
                                if ($state['orderable_id'] !== null) {
                                    $orderItemPrice = static::getAmount($state['orderable_type'], $state['orderable_id']);
                                    $set('amount', $orderItemPrice * $get('quantity'));
                                    $set('../../total_amount', static::getTotalAmount($get('../')));
                                }
                            })
                            ->required()
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('quantity')
                            ->live()
                            ->default(25)
                            ->required()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                if ($get('orderable_id') !== null) {
                                    $orderItemPrice = static::getAmount($get('orderable_type'), $get('orderable_id'));
                                    $set('amount', $orderItemPrice * $state);
                                    $set('../../total_amount', static::getTotalAmount($get('../')));
                                }
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('amount')
                            ->prefix('₱')
                            ->live()
                            ->readOnly()
                            ->required()
                            ->columnSpan(4),
                    ])
                    ->reorderable()
                    ->deleteAction(
                        fn(Action $action) => $action->requiresConfirmation(),
                    )
                    ->columns(12)
            ]),
            Forms\Components\Section::make([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->columnSpan(1),
                Forms\Components\Select::make('promo_id')
                    ->preload()
                    ->relationship('promo', 'name')
                    ->nullable()
                    ->live()
                    // ->readOnly(fn ($get) => $get('total_amount') > $get('deducted_amount'))
                    ->afterStateUpdated(function ($state, $get, $set) {
                        $promo = $state ? Promo::find($state) : null;
                        $deductedAmount = 0;
                        if ($promo->type == 'percentage') {
                            $deductedAmount = $promo->value / 100 * $get('total_amount');
                        } else {
                            $deductedAmount = $promo->value;
                        }
                        $set('deducted_amount', $deductedAmount);
                        $set('total_amount', $get('total_amount') - $deductedAmount);
                    })
                    ->columnSpan(2),
                Forms\Components\TextInput::make('deducted_amount')
                    ->live()
                    ->readOnly()
                    ->default(0)
                    ->prefix('₱')
                    ->numeric()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('total_amount')
                    ->readOnly()
                    ->default(0)
                    ->prefix('₱')
                    ->required()
                    ->numeric()
                    ->live()
                    ->columnSpan(3),
            ])
                ->columns(8)

        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    protected static function getAmount($orderableType, $orderableId): float
    {
        if ($orderableType === 'App\Models\Utility') {
            return Utility::where('id', $orderableId)->pluck('price')->first();
        } else if ($orderableType === 'App\Models\Package') {
            return Package::where('id', $orderableId)->pluck('price')->first();
        } else if ($orderableType === 'App\Models\Food') {
            return Food::where('id', $orderableId)->pluck('price')->first();
        }
    }

    protected static function getTotalAmount($orderItems): float
    {
        $totalAmount = 0;
        foreach ($orderItems as $orderItem) {
            $totalAmount += $orderItem['amount'];
        }
        return $totalAmount;
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
                    ->dateTime('M j, Y g:i A'),
                Tables\Columns\TextColumn::make('end')
                    ->sortable()
                    ->dateTime('M j, Y g:i A'),
                Tables\Columns\TextColumn::make('caterer.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'amber',
                        'paid' => 'green',
                        'completed' => 'blue',
                        'cancelled' => 'red',
                    }),
                Tables\Columns\TextColumn::make('promo_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
}
