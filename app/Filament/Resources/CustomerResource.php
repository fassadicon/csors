<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\FormsComponent;
use App\Filament\Exports\UserExporter;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    // protected static ?string $navigationGroup = 'Authorization';
    protected static ?string $navigationLabel = 'Customers';
    protected static ?string $breadcrumb = 'Customers';
    public static ?string $pluralModelLabel = 'Customers';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Username')
                    ->required()
                    ->maxLength(255)->readOnly(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)->readOnly(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('phone_number')
                    ->nullable()
                    ->maxLength(255)->readOnly(),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)->readOnly(),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)->readOnly(),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255)->readOnly(),
                Forms\Components\TextInput::make('ext_name')
                    ->maxLength(255)->readOnly(),
                Forms\Components\Toggle::make('is_verified')
                    ->disabled(fn(Get $get) => $get('verification_image_path') == null)
                    ->visible(auth()->user()->hasRole('superadmin')),
                // ->columnSpan(2),
                Forms\Components\FileUpload::make('verification_image_path')
                    ->visible(auth()->user()->hasRole('superadmin'))
                    ->label('Valid ID')
                    ->directory('users/' . auth()->id() . '/verification')
                    ->nullable()
                    ->columnSpan(3)->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(UserExporter::class)
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('is_customer', 1)
                            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                                $query->whereHas('orders', function ($query) {
                                    $query->where('caterer_id', auth()->user()->caterer->id);
                                });
                            });
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_verified')
                    ->formatStateUsing(fn(string $state) => $state == 1 ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn($record) => $record->is_verified == 1 ? 'success' : 'danger')
                    ->label('Verified'),
                Tables\Columns\TextColumn::make('orders_count')
                    ->formatStateUsing(function (Model $record) {
                        if (auth()->user()->hasRole('caterer')) {
                            return $record->orders->where('caterer_id', auth()->user()->caterer->id)->count();
                        }
                        return $record->orders->count();
                    })
                    ->label('# of Orders'),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
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
                Tables\Actions\ViewAction::make()
                    ->visible(auth()->user()->hasRole('caterer')),
                Tables\Actions\EditAction::make()
                    ->visible(auth()->user()->hasRole('superadmin')),
                // Tables\Actions\DeleteAction::make()
                //     ->visible(auth()->user()->hasRole('superadmin')),
                // Tables\Actions\RestoreAction::make()
                //     ->visible(auth()->user()->hasRole('superadmin')),

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
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->whereHas('orders', function ($query) {
                    $query->where('caterer_id', auth()->user()->caterer->id);
                });
            })
            ->where('is_customer', 1)
            ->withCount('orders')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('is_customer', 1)->count();
    // }
}
