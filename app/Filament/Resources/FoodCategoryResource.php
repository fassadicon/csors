<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodCategoryResource\Pages;
use App\Filament\Resources\FoodCategoryResource\RelationManagers;
use App\Filament\Resources\FoodCategoryResource\RelationManagers\FoodDetailsRelationManager;
use App\Models\FoodCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class FoodCategoryResource extends Resource
{
    protected static ?string $model = FoodCategory::class;

    protected static ?string $navigationGroup = 'Food Options';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('caterer_id')
                    ->relationship('caterer', 'name')
                    ->visible(auth()->user()->hasRole('superadmin'))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextArea::make('description')
                    ->nullable(),
                Forms\Components\FileUpload::make('images')
                    ->directory('caterers/images/food-categories')
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
                Tables\Columns\TextColumn::make('caterer.name')
                    ->visible(auth()->user()->hasRole('superadmin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
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
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => FoodCategoryResource::getUrl('logs', ['record' => $record]))
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
            FoodDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFoodCategories::route('/'),
            'create' => Pages\CreateFoodCategory::route('/create'),
            'view' => Pages\ViewFoodCategory::route('/{record}'),
            'edit' => Pages\EditFoodCategory::route('/{record}/edit'),
            'logs' => Pages\LogFoodCategory::route('/{record}/logs')
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
        })->count();
    }
}
