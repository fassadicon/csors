<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServingTypeResource\Pages;
use App\Filament\Resources\ServingTypeResource\RelationManagers;
use App\Filament\Resources\ServingTypeResource\RelationManagers\FoodDetailsRelationManager;
use App\Models\ServingType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ServingTypeResource extends Resource
{
    protected static ?string $model = ServingType::class;

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
                TinyEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->directory('caterers/images/serving-types')
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
            FoodDetailsRelationManager::class,
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
            'index' => Pages\ListServingTypes::route('/'),
            'create' => Pages\CreateServingType::route('/create'),
            'view' => Pages\ViewServingType::route('/{record}'),
            'edit' => Pages\EditServingType::route('/{record}/edit'),
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
