<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Caterer;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Exports\CatererExporter;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CatererResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CatererResource\RelationManagers;
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
                    ->required()
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255)
                    ->disabledOn('edit'),
                TinyEditor::make('about')
                    ->columnSpanFull()->disabledOn('edit'),
                Forms\Components\FileUpload::make('logo_path')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/images/logo')
                    ->label('Logo')
                    ->image()
                    ->nullable()
                    ->visibleOn('edit')->disabledOn('edit'),
                Forms\Components\FileUpload::make('qr_path')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/images/qr')
                    ->label('QR Payment')
                    ->image()
                    ->nullable()
                    ->image()
                    ->visibleOn('edit')->disabledOn('edit'),
                Forms\Components\FileUpload::make('requirements_path')
                    ->directory(fn($record) => 'caterers/' . $record->id . '/requirements')
                    ->label('Business Requirements (.zip)')
                    ->nullable()->disabledOn('edit')
                    ->downloadable()
                    ->columnSpanFull(),
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
                    ->visibleOn('edit')->disabledOn('edit'),
                Forms\Components\Toggle::make('is_verified')
                    ->disabled(fn(Get $get) => $get('requirements_path') == null)
                    ->label('Verified?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(CatererExporter::class)
            ])
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
                    ->color(fn($record) => $record->is_verified == 1 ? 'success' : 'danger')
                    ->formatStateUsing(function ($record) {
                        return $record->is_verified == 1 ? 'Yes' : 'No';
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
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => CatererResource::getUrl('logs', ['record' => $record]))
                    ->icon('heroicon-m-list-bullet')
                    ->color('gray'),
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
            'logs' => Pages\LogCaterer::route('/{record}/logs')
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
