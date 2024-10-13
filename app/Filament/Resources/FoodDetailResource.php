<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodDetailResource\Pages;
use App\Filament\Resources\FoodDetailResource\RelationManagers;
use App\Filament\Resources\FoodDetailResource\RelationManagers\ServingTypesRelationManager;
use App\Models\FoodCategory;
use App\Models\FoodDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                        // modifyQueryUsing: fn(Builder $query) => $query->where('caterer_id', auth()->user()->caterer->id),
                        modifyQueryUsing: fn(Builder $query) => $query->where('caterer_id', auth()->user()->caterer->id),
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
            ->columns([
                Tables\Columns\TextColumn::make('foodCategory.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
