<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Food;
use Filament\Tables;
use App\Models\Package;
use App\Models\Utility;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use App\Filament\Resources\PackageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PackageResource\RelationManagers;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationGroup = 'Package Options';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                Forms\Components\Select::make('events')
                    ->relationship(
                        'events',
                        'name',
                        fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                            $query->where('caterer_id', auth()->user()->caterer->id);
                        })
                    )
                    ->multiple()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->nullable(),
                Forms\Components\Section::make([
                    Forms\Components\Repeater::make('packageItems')
                        ->label('Package List')
                        ->relationship()
                        ->schema([
                            Forms\Components\MorphToSelect::make('packageable')
                                ->label('Package Item')
                                ->preload()
                                ->types([
                                    MorphToSelect\Type::make(Utility::class)
                                        ->modifyOptionsQueryUsing(fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                                            $query->where('caterer_id', auth()->user()->caterer->id);
                                        }))
                                        ->getOptionLabelFromRecordUsing(fn(Utility $record): string => "$record->name - (₱$record->price/[pc/set])"),
                                    MorphToSelect\Type::make(Food::class)
                                        ->modifyOptionsQueryUsing(fn(Builder $query) => $query->when(auth()->user()->hasRole('caterer'), function ($query) {
                                            $query->whereHas('servingType', function ($query) {
                                                $query->where('caterer_id', auth()->user()->caterer->id);
                                            });
                                        }))
                                        ->getOptionLabelFromRecordUsing(fn(Food $record): string =>
                                        $record->foodDetail->name .  " - " . $record->servingType->name . " (₱" . $record->price . "/pax)"),
                                ])
                                // ->afterStateUpdated(function ($state, $get, $set) {
                                //     $set('amount', static::getAmount($state['orderable_type'], $state['orderable_id'], $get('quantity')));

                                //     $totalAmount = static::getTotalAmount($get('../'));
                                //     $deductedAmount = static::getDeductedAmount($get('../../promo_id'), $totalAmount);
                                //     $set('../../deducted_amount', $deductedAmount);
                                //     $set('../../total_amount', $totalAmount - $deductedAmount);
                                // })
                                ->live(debounce: 500)
                                ->required()
                                ->columnSpanFull(),
                            // Forms\Components\TextInput::make('quantity')
                            //     ->minValue(1)
                            //     ->live(debounce: 500)
                            //     ->default(25)
                            //     ->integer()
                            //     ->required()
                            //     ->afterStateUpdated(function ($state, $get, $set) {
                            //         $set('amount', static::getAmount($get('orderable_type'), $get('orderable_id'), $state));

                            //         $totalAmount = static::getTotalAmount($get('../'));
                            //         $deductedAmount = static::getDeductedAmount($get('../../promo_id'), $totalAmount);
                            //         $set('../../deducted_amount', $deductedAmount);
                            //         $set('../../total_amount', $totalAmount - $deductedAmount);
                            //     })
                            //     ->columnSpan(2),
                            // Forms\Components\TextInput::make('amount')
                            //     ->prefix('₱')
                            //     ->live()
                            //     ->readOnly()
                            //     ->required()
                            //     ->columnSpan(4),
                        ])
                        // ->afterStateUpdated(function ($get, $set) {
                        //     $totalAmount = static::getTotalAmount($get('orderItems'));
                        //     $deductedAmount = static::getDeductedAmount($get('promo_id'), $totalAmount);
                        //     $set('deducted_amount', $deductedAmount);
                        //     $set('total_amount', $totalAmount - $deductedAmount);
                        // })
                        ->reorderable()
                        ->columns(12)
                ]),
                Forms\Components\FileUpload::make('images')
                    ->directory('caterers/images/packages')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->openable()
                    ->panelLayout('grid')
                    ->uploadingMessage('Uploading images...')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('events.caterer.name')
                    ->label('Caterer')
                    ->searchable()
                    ->visible(auth()->user()->hasRole('superadmin')),

                Tables\Columns\TextColumn::make('events.name')
                    ->label('Events')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('packageItems')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->formatStateUsing(function ($state) {
                        $packageable_type = get_class($state->packageable);
                        if ($packageable_type === 'App\Models\Food') {
                            return $state->packageable->foodDetail->name . ' - ' . $state->packageable->servingType->name;
                        }

                        return $state->packageable->name;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->money('php')
                    ->sortable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => PackageResource::getUrl('logs', ['record' => $record]))
                    ->icon('heroicon-m-list-bullet')
                    ->color('gray'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        if (auth()->user()->hasRole('superadmin')) {
            return false;
        }
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
            'logs' => Pages\LogPackage::route('/{record}/logs')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->whereHas('events', function ($query) {
                    $query->where('caterer_id', auth()->user()->caterer->id);
                });
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::when(auth()->user()->hasRole('caterer'), function ($query) {
            $query->whereHas('events', function ($query) {
                $query->where('caterer_id', auth()->user()->caterer->id);
            });
        })->count();
    }
}
