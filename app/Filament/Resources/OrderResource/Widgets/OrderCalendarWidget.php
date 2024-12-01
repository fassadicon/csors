<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Food;
use App\Models\Order;
use App\Models\Package;
use App\Models\Utility;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\OrderResource;
use Filament\Actions\Action;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class OrderCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Order::class;
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $query = Order::query()
            ->with(['user', 'caterer', 'orderItems', 'promo'])
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                $query->where('caterer_id', auth()->user()->caterer->id);
            })
            ->where('order_status', OrderStatus::Confirmed)
            ->orWhereIn('payment_status', [PaymentStatus::Paid, PaymentStatus::Partial])
            ->where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end']);

        return $query->get()
            ->map(function (Order $order): EventData {
                $backgroundColor = match ($order->status) {
                    'pending' => 'orange',
                    'paid' => 'green',
                    'cancelled' => 'red',
                    default => 'blue',
                };

                return EventData::make()
                    ->id($order->id)
                    ->title('Order #' . $order->id)
                    ->start($order->start)
                    ->end($order->end)
                    ->backgroundColor($backgroundColor)
                    ->url(
                        url: OrderResource::getUrl(name: 'view', parameters: ['record' => $order]),
                        shouldOpenUrlInNewTab: true
                    )
                ;
            })
            ->toArray();
    }

    public function config(): array
    {
        return [
            'headerToolbar' => [
                'left' => 'prev,next today listMonth',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
            ],
            'navLinks' => 'true',
        ];
    }

    protected function headerActions(): array
    {
        if (auth()->user()->hasRole('superadmin')) {
            return [];
        }

        return [
            Action::make('create')
                ->url(OrderResource::getUrl('create'))
                ->openUrlInNewTab()
        ];
    }

    public function getFormSchema(): array
    {
        return OrderResource::getFormSchema();
    }

    protected static function getAmount($orderableType, $orderableId): float
    {
        if ($orderableType === 'App\Models\Utility') {
            return Utility::where('id', $orderableId)->pluck('price')->first();
        } else if ($orderableType === 'App\Models\Package') {
            return Package::where('id', $orderableId)->pluck('price')->first();
        } else if ($orderableType === 'App\Models\Food') {
            return Food::where('id', $orderableId)->pluck('price')->first();
        }
    }

    protected static function getTotalAmount($orderItems): float
    {
        $totalAmount = 0;
        foreach ($orderItems as $orderItem) {
            $totalAmount += $orderItem['amount'];
        }
        return $totalAmount;
    }
}
