<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\OrderResource\Widgets\CatererOrderCalendarWidget;

class CalendarOrders extends Page
{
    protected static string $view = 'filament.pages.calendar-orders';

    protected static ?string $title = 'Orders Calendar - CSORS';
    protected static ?string $navigationLabel = 'Orders Calendar';
    protected static ?string $slug = 'orders/calendar';
    protected ?string $heading = 'Orders Calendar';
    protected static ?string $navigationGroup = 'Orders';

    protected function getHeaderWidgets(): array {
        return [
            CatererOrderCalendarWidget::class,
        ];
    }
}
