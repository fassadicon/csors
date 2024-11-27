<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportedUserResource\Pages;
use App\Models\Caterer;
use App\Models\ReportedUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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

        // Remove the global scope for soft deleting if needed
        $query->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

        // Filter the query for other users based on user_id
        $query->where('user_id', auth()->id());

        // Return the modified query
        return $query;
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
                TextColumn::make('reported_user')
                    ->label('Reported Customer')
                    ->getStateUsing(function ($record) {
                        // return $record->reported_user->name;
                        // dd($record['reported_user']);
                        return User::findOrFail($record['reported_user'])->name;
                    })
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

                TextColumn::make('reported')
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
                Tables\Filters\TrashedFilter::make()
                    ->label('Status')
                    ->placeholder('Blocked Only')
                    ->trueLabel('All')
                    ->falseLabel('Unblocked Only'),
            ])
            ->actions([
                // You may add these actions to your table if you're using a simple
                // resource, or you just want to be able to delete records without
                // leaving the table.
                Tables\Actions\DeleteAction::make()->label('Block')
                    ->modalHeading('Are you sure you want to block this user?')
                    ->modalSubheading('This action can be reverse.')
                    ->modalButton('Yes, Block'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()->label('Unblock'),
                // ...
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon('heroicon-o-no-symbol'),
                Tables\Actions\RestoreBulkAction::make()->label('Unblock')
                    ->modalHeading('Are you sure you want to unblock this user?')
                    ->modalSubheading('This action can be reverse.')
                    ->modalButton('Yes, Unblock'),
                // ...
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
            // 'create' => Pages\CreateReportedUser::route('/create'),
            // 'edit' => Pages\EditReportedUser::route('/{record}/edit'),
        ];
    }
}
