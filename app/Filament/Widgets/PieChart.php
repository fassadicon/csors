<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class PieChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Status';

    protected int | string | array $columnSpan = 1;

    public $pendingPaymentOrdersCount;
    public $partialPaymentOrdersCount;
    public $paidPaymentOrdersCount;
    public $refundedPaymentOrdersCount;

    protected function getData(): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $caterer_id = auth()->user()->caterer->id;

            $this->pendingPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
                $query->where('caterer_id', $caterer_id);
            })->where('payment_status', 'pending')->count();
            $this->partialPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
                $query->where('caterer_id', $caterer_id);
            })->where('payment_status', 'partial')->count();
            $this->paidPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
                $query->where('caterer_id', $caterer_id);
            })->where('payment_status', 'paid')->count();
            $this->refundedPaymentOrdersCount = Order::when(auth()->user()->hasRole('caterer'), function ($query) use ($caterer_id) {
                $query->where('caterer_id', $caterer_id);
            })->where('payment_status', 'refunded')->count();
        } else {
            $this->pendingPaymentOrdersCount = Order::where('payment_status', 'pending')->count();
            $this->partialPaymentOrdersCount = Order::where('payment_status', 'partial')->count();
            $this->paidPaymentOrdersCount = Order::where('payment_status', 'paid')->count();
            $this->refundedPaymentOrdersCount = Order::where('payment_status', 'refunded')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [
                        $this->pendingPaymentOrdersCount,
                        $this->partialPaymentOrdersCount,
                        $this->paidPaymentOrdersCount,
                        $this->refundedPaymentOrdersCount
                    ],
                    'backgroundColor' => [
                        '#FFA500',
                        '#FFD700',
                        '#4CAF50',
                        '#FF6347',
                    ],
                    // 'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Pending', 'Partial', 'Completed', 'Refunded'],
        ];
    }
    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ],
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }
}
