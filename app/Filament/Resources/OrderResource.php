<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Package;
use App\Models\Utility;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    // See
    // https://laraveldaily.com/post/filament-repeater-live-calculations-on-update/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Details')
                        ->icon('heroicon-m-bars-3-bottom-left')
                        ->columns([
                            'sm' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->preload()
                                ->relationship('user', 'name')
                                ->required(),
                            Forms\Components\Select::make('caterer_id')
                                ->preload()
                                ->relationship('caterer', 'name')
                                ->required(),
                            Forms\Components\DateTimePicker::make('start')
                                ->required(),
                            Forms\Components\DateTimePicker::make('end')
                                ->afterOrEqual('start')
                                ->required(),
                            Forms\Components\Textarea::make('remarks'),
                        ]),
                    Wizard\Step::make('Products')
                        ->icon('heroicon-m-shopping-bag')
                        ->schema([
                            Forms\Components\Repeater::make('orderItems')
                                ->columns([
                                    'sm' => 2,
                                    'xl' => 5,
                                ])
                                ->schema([
                                    Forms\Components\Select::make('orderable_type')
                                        ->label('Type')
                                        ->options([
                                            'App\Models\Food' => 'Food',
                                            'App\Models\Utility' => 'Utility',
                                            'App\Models\Package' => 'Package',
                                        ])
                                        ->live()
                                        ->required(),
                                    Forms\Components\Select::make('orderable_id')
                                        ->options(function (Get $get) {
                                            switch ($get('orderable_type')) {
                                                case 'App\Models\Package':
                                                    return Package::get()->pluck('name', 'id');
                                                    // case 'App\Models\Food':
                                                    //     return Food::get()->pluck('name', 'id');  // Food Detail and Serving Type
                                                case 'App\Models\Utility':
                                                    return Utility::get()->pluck('name', 'id');
                                                default:
                                                    return [];
                                            }
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->required()
                                        ->afterStateUpdated(function ($state, $get, $set) {
                                            $orderableType = $get('orderable_type');
                                            $price = null;

                                            switch ($orderableType) {
                                                case 'Package':
                                                    $price = Package::find($state)?->price;
                                                    break;
                                                case 'Utility':
                                                    $price = Utility::find($state)?->price;
                                                    break;
                                            }

                                            $set('price', $price);
                                            $quantity = $get('quantity') ?? 25;
                                            $set('amount', $price * $quantity);
                                        }),
                                    Forms\Components\TextInput::make('price')
                                        ->live()
                                        ->readonly()
                                        ->required(),
                                    Forms\Components\TextInput::make('quantity')
                                        ->live()
                                        ->default(25)
                                        ->required()
                                        ->afterStateUpdated(function ($state, $get, $set) {
                                            $set('amount', $get('price') * $state);
                                        }),
                                    Forms\Components\TextInput::make('amount')
                                        ->live()
                                        ->readOnly()
                                        ->required(),
                                ])
                                // ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->deleteAction(
                                    fn(Action $action) => $action->requiresConfirmation(),
                                )
                                ->columns(2)
                        ]),
                    Wizard\Step::make('Billing')
                        ->icon('heroicon-m-credit-card')
                        ->columns([
                            'sm' => 1,
                            'xl' => 3,
                        ])
                        ->schema([
                            Forms\Components\Select::make('promo_id')
                                ->preload()
                                ->relationship('promo', 'name')
                                ->nullable()
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    $promo = $state ? Promo::find($state) : null;
                                    $deductedAmount = 0;
                                    if ($promo->type == 'percentage') {
                                        $deductedAmount = $promo->value / 100 * $get('total_amount');
                                    } else {
                                        $deductedAmount = $promo->value;
                                    }
                                    $set('deducted_amount', $deductedAmount);
                                }),
                            Forms\Components\TextInput::make('deducted_amount')
                                ->readOnly()
                                ->default(0)
                                ->prefix('₱')
                                ->numeric(),
                            // Forms\Components\TextInput::make('payment_id')
                            //     ->numeric(),
                            Forms\Components\TextInput::make('total_amount')
                                ->prefix('₱')
                                ->required()
                                ->numeric(),
                            // Get the sum from the order item repeater
                        ]),
                ])
                    // ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    //     <x-filament::button
                    //         type="submit"
                    //         size="sm"
                    //     >
                    //         Submit
                    //     </x-filament::button>
                    // BLADE))) // Find a way to remove the form button in create/edit form OR try to implement wizard directly into the resource
                    ->columnSpanFull()
            ]);
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
