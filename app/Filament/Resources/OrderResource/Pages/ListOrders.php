<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pending';
    }

    public function getTabs(): array
    {

        $tabs = [
            'pending' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getPendingOrders())
                ->badge($this->getPendingOrders()->count())
                ->badgeColor('amber'),
            'confirmed' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByOrderStatus(OrderStatus::Confirmed))
                ->badge($this->getOrderByOrderStatus(OrderStatus::Confirmed)->count())
                ->badgeColor('blue'),
            'completed' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByOrderStatus(OrderStatus::Completed))
                ->badge($this->getOrderByOrderStatus(OrderStatus::Completed)->count())
                ->badgeColor('success'),
            'declined' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByOrderStatus(OrderStatus::Declined))
                ->badge($this->getOrderByOrderStatus(OrderStatus::Declined)->count())
                ->badgeColor('danger'),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn() => $this->getOrderByOrderStatus(OrderStatus::Cancelled))
                ->badge($this->getOrderByOrderStatus(OrderStatus::Cancelled)->count())
                ->badgeColor('danger'),
            // 'payment_pending' => Tab::make('Pending Payment')
            //     ->modifyQueryUsing(fn() => $this->getOrderByPaymentStatus(PaymentStatus::Pending))
            //     ->badge($this->getOrderByPaymentStatus(PaymentStatus::Pending)->count())
            //     ->badgeColor('amber'),
            'payment_partial' => Tab::make('Partial Payment')
                ->modifyQueryUsing(fn() => $this->getOrderByPaymentStatus(PaymentStatus::Partial))
                ->badge($this->getOrderByPaymentStatus(PaymentStatus::Partial)->count())
                ->badgeColor('blue'),
            'payment_paid' => Tab::make('Paid')
                ->modifyQueryUsing(fn() => $this->getOrderByPaymentStatus(PaymentStatus::Paid))
                ->badge($this->getOrderByPaymentStatus(PaymentStatus::Paid)->count())
                ->badgeColor('success'),
            'payment_refunded' => Tab::make('Refunded')
                ->modifyQueryUsing(fn() => $this->getOrderByPaymentStatus(PaymentStatus::Refunded))
                ->badge($this->getOrderByPaymentStatus(PaymentStatus::Refunded)->count())
                ->badgeColor('amber'),
        ];

        // Add the 'all' tab
        if (auth()->user()->hasRole('superadmin')) {
            $allTab = [
                'all' => Tab::make()
                    ->badge(Order::get()->count())
                    ->badgeColor('gray')
            ];
        } else {
            $allTab = [
                'all' => Tab::make()
                    ->badge(Order::query()->where('caterer_id', auth()->user()->caterer->id)->count())
                    ->badgeColor('red')
            ];
        }

        $tabs = array_merge($allTab, $tabs);

        return $tabs;
    }

    protected function getOrderByOrderStatus($orderStatus): Builder
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Order::query()
                // ->where('caterer_id', auth()->user()->caterer->id)
                ->where('order_status', $orderStatus);
        } else {
            return Order::query()
                ->where('caterer_id', auth()->user()->caterer->id)
                ->where('order_status', $orderStatus);
        }
    }

    protected function getOrderByPaymentStatus($paymentStatus): Builder
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Order::query()
                // ->where('caterer_id', auth()->user()->caterer->id)
                ->where('payment_status', $paymentStatus);
        } else {
            return Order::query()
                ->where('caterer_id', auth()->user()->caterer->id)
                ->where('payment_status', $paymentStatus);
        }
    }

    protected function getPendingOrders(): Builder
    {
        if (auth()->user()->hasRole('superadmin')) {
            return Order::query()
                ->where('order_status', OrderStatus::Pending)
                ->orWhere('payment_status', PaymentStatus::Pending);
        } else {
            return Order::query()
                ->where('caterer_id', auth()->user()->caterer->id)
                ->where('order_status', OrderStatus::Pending)
                ->orWhere('payment_status', PaymentStatus::Pending);
        }
    }
}
