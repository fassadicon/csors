<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServingTypeResource\Pages;
use App\Filament\Resources\ServingTypeResource\RelationManagers;
use App\Models\ServingType;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServingTypeResource extends Resource
{
    protected static ?string $model = ServingType::class;

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('description')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListServingTypes::route('/'),
            'create' => Pages\CreateServingType::route('/create'),
            'edit' => Pages\EditServingType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $caterer_id = auth()->user()->caterer->id;
        return parent::getEloquentQuery()->where('caterer_id', $caterer_id)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
