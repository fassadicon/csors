<?php

namespace App\Filament\Resources;

use App\Enums\CancellationRequestStatus;
use App\Enums\OrderStatus;
use App\Filament\Resources\CancellationRequestResource\Pages;
use App\Filament\Resources\CancellationRequestResource\RelationManagers;
use App\Models\CancellationRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CancellationRequestResource extends Resource
{
    protected static ?string $model = CancellationRequest::class;

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->searchable()
                    ->preload()
                    ->relationship(
                        'order',
                        'id',
                        modifyQueryUsing: function ($query) {
                            return $query
                                ->when(auth()->user()->hasRole('caterer'), function ($query) {
                                    $query->where('caterer_id', auth()->user()->caterer->id);
                                })
                                ->whereIn('order_status', [OrderStatus::Confirmed, OrderStatus::Pending]);
                        }
                    )
                    ->disabled(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->default(CancellationRequestStatus::Pending)
                    ->options(CancellationRequestStatus::class)
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->disabled(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                    ->required(),
                Forms\Components\Textarea::make('response')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.id')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.caterer.name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('response'),
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
                Tables\Filters\SelectFilter::make('status')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('logs')
                    ->url(fn($record) => CancellationRequestResource::getUrl('logs', ['record' => $record]))
                    ->icon('heroicon-m-list-bullet')
                    ->color('gray'),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //     Tables\Actions\ForceDeleteBulkAction::make(),
                //     Tables\Actions\RestoreBulkAction::make(),
                // ]),
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
            'index' => Pages\ListCancellationRequests::route('/'),
            'create' => Pages\CreateCancellationRequest::route('/create'),
            'view' => Pages\ViewCancellationRequest::route('/{record}'),
            'edit' => Pages\EditCancellationRequest::route('/{record}/edit'),
            'logs' => Pages\LogCancellationRequest::route('/{record}/logs')
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable creating new records
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('caterer_id', auth()->user()->caterer->id);
                });
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::when(auth()->user()->hasRole('caterer'), function ($query) {
            $query->whereHas('order', function ($query) {
                $query->where('caterer_id', auth()->user()->caterer->id);
            });
        })
            ->where('status', 'pending')
            ->count();
    }
}
