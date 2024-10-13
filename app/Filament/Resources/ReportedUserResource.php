<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportedUserResource\Pages;
use App\Models\Caterer;
use App\Models\ReportedUser;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportedUserResource extends Resource
{
    protected static ?string $model = ReportedUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('superadmin')) {
            // If the user is a superadmin, show all records
            return $query;
        }

        // For other users, filter based on user_id
        return $query->where('user_id', auth()->id());
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')->required(),
                TextInput::make('reported_user')->required(),
                TextInput::make('comment')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // for caterer 
                TextColumn::make('user.name')
                    ->label('Reported Customer')
                    ->visible(!auth()->user()->hasRole('superadmin')),

                // for super admin
                TextColumn::make('account_type')
                    ->label('Account Type')
                    ->getStateUsing(function ($record) {
                        return $record->user->is_customer === 0 ? 'Caterer' : 'Customer';
                    })
                    ->visible(auth()->user()->hasRole('superadmin')),
                TextColumn::make('user.id')
                    ->label('Reporter')
                    ->getStateUsing(function ($record) {
                        return $record->user->is_customer === 0
                            ? Caterer::where('user_id', $record->user_id)->first()->name ?? 'Unknown Caterer'
                            : $record->user->name;
                    })
                    ->visible(auth()->user()->hasRole('superadmin')),

                TextColumn::make('reported_user')
                    ->label('Reported User')
                    ->getStateUsing(function ($record) {
                        return $record->user->is_customer === 1
                            ? Caterer::where('user_id', $record->reported_user)->first()->name ?? 'Unknown Caterer'
                            : $record->user->name;
                    })
                    ->visible(auth()->user()->hasRole('superadmin')),

                TextColumn::make('comment'),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                // Add your filters here if needed
            ])
            ->actions([
                // Actions for the table
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false; // Disable creating new records
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportedUsers::route('/'),
            'create' => Pages\CreateReportedUser::route('/create'),
            'edit' => Pages\EditReportedUser::route('/{record}/edit'),
        ];
    }
}
