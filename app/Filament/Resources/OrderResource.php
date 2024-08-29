<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use App\Models\Order;
use App\Models\Package;
use App\Models\Utility;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    public function getOrderableOptions($type)
    {
        switch ($type) {
            case Package::class:
                return Package::get()->pluck('name', 'id');
            case Food::class:
                return Food::get()->pluck('name', 'id');
            case Utility::class:
                return Utility::get()->pluck('name', 'id');
            default:
                return [];
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Details')
                        ->columns([
                            'sm' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            // Forms\Components\Select::make('user_id')
                            //     ->preload()
                            //     ->relationship('user', 'name')
                            //     ->required(),
                            // Forms\Components\Select::make('caterer_id')
                            //     ->preload()
                            //     ->relationship('caterer', 'name')
                            //     ->required(),
                            // Forms\Components\DateTimePicker::make('start')
                            //     ->required(),
                            // Forms\Components\DateTimePicker::make('end')
                            //     ->afterOrEqual('start')
                            //     ->required(),
                            // Forms\Components\Textarea::make('remarks'),
                        ]),
                    Wizard\Step::make('Products')
                        ->schema([
                            Forms\Components\Repeater::make('orderItems')
                                ->columns([
                                    'sm' => 2,
                                    'xl' => 5,
                                ])
                                ->schema([
                                    // MorphToSelect::make('orderable')
                                    //     ->types([
                                    //         MorphToSelect\Type::make(Package::class)
                                    //             ->titleAttribute('name'),
                                    //         MorphToSelect\Type::make(Food::class)
                                    //             ->titleAttribute('title'),
                                    //         MorphToSelect\Type::make(Utility::class)
                                    //             ->titleAttribute('title'),
                                    //     ]),
                                    Forms\Components\Select::make('orderable_type')
                                        ->label('Type')
                                        ->options([
                                            'Food' => 'Food',
                                            'Utility' => 'Utility',
                                            'Package' => 'Package',
                                        ])
                                        ->live()
                                        ->required(),
                                    Forms\Components\Select::make('orderable_id')
                                        ->options(function (Get $get) {
                                            switch ($get('orderable_type')) {
                                                case 'Package':
                                                    return Package::get()->pluck('name', 'id');
                                                    // case 'Food':
                                                    //     return Food::get()->pluck('name', 'id');
                                                case 'Utility':
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
                                            // Fetch price based on the selected value
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

                                            // Set the price field with the fetched price
                                            $set('price', $price);
                                        }),
                                    Forms\Components\TextInput::make('price')
                                        // ->formatStateUsing(function (Get $get) {
                                        //     switch ($get('orderable_type')) {
                                        //         case 'Package':
                                        //             return Package::where('id', $get('orderable_id'))->first()->pluck('price');
                                        //             // case 'Food':
                                        //             //     return Food::where('id', $get['orderable_id'])->first()->pluck('price');
                                        //         case 'Utility':
                                        //             return Utility::where('id', $get('orderable_id'))->first()->pluck('price');
                                        //         default:
                                        //             return null;
                                        //     }
                                        // })
                                        ->live()
                                        ->readonly()
                                        ->required(),
                                    Forms\Components\TextInput::make('quantity')
                                        ->required(),
                                    Forms\Components\TextInput::make('amount')
                                        ->required(),
                                ])
                                ->columns(2)
                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            Forms\Components\Select::make('promo_id')
                                ->preload()
                                ->relationship('promo', 'name')
                                ->required(),
                            // Forms\Components\TextInput::make('payment_id')
                            //     ->numeric(),
                            // Forms\Components\TextInput::make('deducted_amount')
                            //     ->numeric(),
                            Forms\Components\TextInput::make('total_amount')
                                ->prefix('₱')
                                ->required()
                                ->numeric(),
                        ]),
                ])
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
                    ->listWithLineBreaks()
                    ->bulleted()
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
