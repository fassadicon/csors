<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Authorization';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->hasRole('superadmin')) {
            return true;
        }
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
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->nullable()
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('password')
                    ->visibleOn('create')
                    ->password()
                    ->required()
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\TextInput::make('ext_name')
                    ->maxLength(255)->disabledOn('edit'),
                Forms\Components\FileUpload::make('verification_image_path')
                    ->label('Valid ID')
                    ->directory('users/' . auth()->id() . '/verification')
                    ->columnSpan(2)
                    ->nullable()->disabledOn('edit'),
                Forms\Components\Toggle::make('is_verified')
                    ->disabled(fn(Get $get) => $get('verification_image_path') == null)
                    ->visible(auth()->user()->hasRole('superadmin')),
                // Forms\Components\Select::make('roles')
                //     ->multiple()
                //     ->relationship('roles', 'name')
                //     // ->getOptionLabelFromRecordUsing(function ($record) {
                //     //     return $record->name == 'guest' ? 'customer' : $record->name;
                //     // })
                //     ->preload()
                //     ->required()->disabled(),
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->default([
                        Role::where('name', 'customer')->pluck('id')->first()
                    ])
                    ->visibleOn(['edit', 'create'])
                    ->required()
                    // ->columnSpanFull()
                    // ->columns(2)
                    ->gridDirection('row')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->formatStateUsing(function ($state) {
                        if ($state != 'guest') {
                            return $state;
                        }
                    })
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable(),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => UserResource::getUrl('logs', ['record' => $record]))
                    ->icon('heroicon-m-list-bullet')
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'logs' => Pages\LogUser::route('/{record}/logs')
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
