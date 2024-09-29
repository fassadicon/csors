<div>
    <x-mary-header title="Order Information"
        subtitle="# {{ $order->id }} - PHP {{ $order->total_amount }}"
        class="!my-2">
        <x-slot:actions>
            <x-mary-badge value="Payment: {{ $order->payment_status->getLabel() }}"
                class="badge-{{ $order->payment_status->getMaryColor() }}" />
            <x-mary-badge value="Order: {{ $order->order_status->getLabel() }}"
                class="badge-{{ $order->order_status->getMaryColor() }}" />
            <a href="{{ route('order-history') }}">
                <x-mary-button label="Back to Order History"
                    class="btn-outline" />
            </a>
        </x-slot:actions>
    </x-mary-header>
    @foreach ($order->orderItems as $orderItem)
        <div>
            <x-mary-list-item :item="$orderItem"
                no-separator
                no-hover>
                <x-slot:avatar>
                    <x-mary-avatar :image="asset('images/placeholder.jpg')" />
                </x-slot:avatar>
                <x-slot:value>
                    @if ($orderItem->orderable_type == 'App\Models\Food')
                        {{ $orderItem->orderable->foodDetail->name }} - {{ $orderItem->orderable->servingType->name }}
                    @else
                        {{ $orderItem->orderable->name }}
                    @endif
                </x-slot:value>
                <x-slot:sub-value>
                    @if ($orderItem->orderable_type == 'App\Models\Food')
                        Pax: {{ $orderItem->quantity }}
                    @else
                        Package/Pieces/Pax: {{ $orderItem->quantity }}
                    @endif
                </x-slot:sub-value>
                <x-slot:actions>
                    PHP {{ $orderItem->amount }}
                </x-slot:actions>
            </x-mary-list-item>
        </div>
    @endforeach
    {{-- <x-mary-header title="Total: {{ $totalAmount }}"
        class="!my-2"
        separator /> --}}
    <x-mary-header title="Customer Information"
        class="!my-2"
        separator />
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Caterer: {{ $order->caterer->name }}
    </p>
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Start: {{ $order->start }}
    </p>
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        End: {{ $order->end }}
    </p>
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Location: {{ $order->location }}
    </p>
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Remarks: {{ $order->remarks }}
    </p>
    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Date Ordered: {{ $order->created_at }}
    </p>

    @if ($order->payment_status->value == 'pending')
        <x-primary-button wire:click='payPartial'>{{ __('Pay Partial') }}</x-primary-button>
        <x-primary-button wire:click='payFull'>{{ __('Pay Full') }}</x-primary-button>
    @elseif ($order->payment_status->value == 'partial')
        <x-primary-button wire:click='payRemaining'>{{ __('Pay Remaining Balance') }}</x-primary-button>
    @endif

    @if ($order->order_status->value == 'pending' || $order->order_status->value == 'confirmed')
        <x-danger-button wire:click='cancel'>{{ __('Request to Cancel') }}</x-danger-button>
    @endif
</div>
