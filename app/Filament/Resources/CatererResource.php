<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatererResource\Pages;
use App\Filament\Resources\CatererResource\RelationManagers;
use App\Models\Caterer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class CatererResource extends Resource
{
    protected static ?string $model = Caterer::class;

    protected static ?string $navigationGroup = 'Authorization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        modifyQueryUsing: function ($query) {
                            return $query->whereHas('roles', function ($query) {
                                $query->where('name', 'caterer');
                            });
                        }
                    )
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255),
                TinyEditor::make('about')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('logo_path')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/images/logo')
                    ->label('Logo')
                    ->image()
                    ->nullable()
                    ->visibleOn('edit'),
                Forms\Components\FileUpload::make('requirements_path')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/requirements')
                    ->label('Business Requirements (.zip)')
                    ->nullable(),
                Forms\Components\FileUpload::make('images')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/images/profile')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->openable()
                    ->preserveFilenames()
                    ->panelLayout('grid')
                    ->uploadingMessage('Uploading images...')
                    ->nullable()
                    ->columnSpanFull()
                    ->visibleOn('edit'),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Verified?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_verified')
                    ->badge()
                    ->color(fn($record) => $record == '1' ? 'success' : 'danger')
                    ->formatStateUsing(function ($record) {
                        return $record == '1' ? 'Yes' : 'No';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('# of Orders'),
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaterers::route('/'),
            'create' => Pages\CreateCaterer::route('/create'),
            'view' => Pages\ViewCaterer::route('/{record}'),
            'edit' => Pages\EditCaterer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('orders')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
