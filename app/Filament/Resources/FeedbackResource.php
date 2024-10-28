<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Feedback;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Components\Rating;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FeedbackResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)->schema([
                // Fetch the current record
                RatingStar::make('rating')->label('Rating')->disabled(),
                Textarea::make('comment')->label('Comment')->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('caterers.name')->label('Customer Name')
                    ->visible(auth()->user()->hasRole('superadmin')),
                TextColumn::make('order.user.name')->label('Customer Name'),
                TextColumn::make('rating')->label(
                    'Rate ⭐'
                ),
                TextColumn::make(
                    'comment'
                )->label('Comment'),
                TextColumn::make('created_at')->label('Date'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
                fn(Model $record): string => Pages\ViewFeedback::getUrl([$record->id]),
            );;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
            'view' => Pages\ViewFeedback::route('/{record}'),
        ];
    }
}
