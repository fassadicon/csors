<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisabledDateResource\Pages;
use App\Filament\Resources\DisabledDateResource\RelationManagers;
use App\Models\DisabledDate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisabledDateResource extends Resource
{
    protected static ?string $model = DisabledDate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('caterer_id')
                    ->relationship('caterer', 'name')
                    ->visible(auth()->user()->hasRole('superadmin'))
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                Forms\Components\TextInput::make('remarks')
                    ->label('Reason/Remarks')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('caterer.name')
                    ->visible(auth()->user()->hasRole('superadmin'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListDisabledDates::route('/'),
            'create' => Pages\CreateDisabledDate::route('/create'),
            'edit' => Pages\EditDisabledDate::route('/{record}/edit'),
        ];
    }
}
