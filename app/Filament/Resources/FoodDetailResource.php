<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\FoodDetail;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\FoodDetailExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FoodDetailResource\Pages;
use App\Filament\Resources\FoodDetailResource\RelationManagers\ServingTypesRelationManager;

class FoodDetailResource extends Resource
{
    protected static ?string $model = FoodDetail::class;

    protected static ?string $navigationGroup = 'Food Options';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('food_category_id')
                    ->relationship(
                        name: 'foodCategory',
                        titleAttribute: 'name',
                        modifyQueryUsing: function ($query) {
                            return $query
                                ->when(auth()->user()->hasRole('caterer'), function ($query) {
                                    $query->where('caterer_id', auth()->user()->caterer->id);
                                });
                        }
                    )
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->directory('caterers/images/food-details')
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
            ->headerActions([
                ExportAction::make()
                    ->exporter(FoodDetailExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('caterer.name')
                    ->searchable()
                    ->sortable()->visible(auth()->user()->hasRole('superadmin')),
                Tables\Columns\TextColumn::make('foodCategory.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servingTypes.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
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
                Tables\Filters\TrashedFilter::make()
                    ->label('Status')
                    ->placeholder('Active Only')
                    ->trueLabel('All')
                    ->falseLabel('Inactive Only')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => FoodDetailResource::getUrl('logs', ['record' => $record]))
                    ->icon('heroicon-m-list-bullet')
                    ->color('gray'),
                Tables\Actions\DeleteAction::make()
                    ->label('Set Inactive')
                    ->icon('heroicon-m-bookmark-slash')
                    ->modalIcon('heroicon-m-bookmark-slash')
                    ->modalHeading('Set Inactive')
                    ->successNotificationTitle('Food detail has been set Inactive.'),
                Tables\Actions\RestoreAction::make()
                    ->label('Set Active')
                    ->icon('heroicon-m-bookmark')
                    ->modalIcon('heroicon-m-bookmark')
                    ->modalHeading('Set Active')
                    ->successNotificationTitle('Food detail has been set Active.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        if (auth()->user()->hasRole('superadmin')) {
            return false;
        }
        return true;
    }


    public static function getRelations(): array
    {
        return [
            ServingTypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFoodDetails::route('/'),
            'create' => Pages\CreateFoodDetail::route('/create'),
            'view' => Pages\ViewFoodDetail::route('/{record}'),
            'edit' => Pages\EditFoodDetail::route('/{record}/edit'),
            'logs' => Pages\LogFoodDetail::route('/{record}/logs')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->whereHas('foodCategory', function ($query) {
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
            $query->whereHas('foodCategory', function ($query) {
                $query->where('caterer_id', auth()->user()->caterer->id);
            });
        })->count();
    }
}
