<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CatererOrderCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Order::class;
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {

        return Order::query()
            ->where('from', '>=', $fetchInfo['start'])
            ->where('to', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Order $event) => EventData::make()
                    ->id($event->id)
                    ->title('test')
                    ->start($event->from)
                    ->end($event->to)
            )
            ->toArray();
    }
}
