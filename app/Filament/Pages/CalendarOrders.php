<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\OrderResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Filament\Resources\OrderResource\Widgets\OrderCalendarWidget;

class CalendarOrders extends Page
{
    use HasPageShield;

    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.pages.calendar-orders';

    protected static ?string $title = 'Orders Calendar - CSORS';
    protected static ?string $navigationLabel = 'Calendar';
    protected static ?string $slug = 'orders/calendar';
    protected ?string $heading = 'Orders Calendar';
    protected static ?string $navigationGroup = 'Order Management';

    protected function getHeaderWidgets(): array {
        return [
            OrderCalendarWidget::class,
        ];
    }
}
