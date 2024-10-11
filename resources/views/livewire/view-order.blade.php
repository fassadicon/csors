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

    @if ($order->cancellationRequest)
        <x-mary-header title="Cancellation Request"
            class="!my-2"
            separator />
        <div class="space-y-2">
            <p>Status: {{ $cancellationRequestStatus }}</p>
            @if ($cancellationRequestStatus->value == 'approved' || $cancellationRequestStatus->value == 'declined')
                <p>Reason: {{ $cancellationRequestReason }}</p>
            @else
                <x-mary-textarea label="Reason for Cancellation"
                    wire:model="cancellationRequestReason"
                    placeholder="Notes for the Caterer"
                    rows="4"
                    inline />
            @endif
            <x-mary-textarea label="Reason for Cancellation"
                wire:model="cancellationRequestResponse"
                placeholder="Awaiting Reply from the Caterer..."
                rows="4"
                inline
                readonly />
        </div>
        <hr class="my-4">
        @if ($cancellationRequestStatus->value == 'pending')
            <x-mary-button wire:click='updateCancellationRequest'
                label="Update Cancellation Request"
                class="btn-primary"
                spinner />
            <x-mary-button wire:click='removeCancellationRequest'
                label="Cancel Cancellation Request"
                class="btn-warning"
                spinner />
        @endif

    @endif

    @if ($order->payment_status->value == 'pending')
        <x-primary-button wire:click='payPartial'>{{ __('Pay Partial') }}</x-primary-button>
        <x-primary-button wire:click='payFull'>{{ __('Pay Full') }}</x-primary-button>
    @elseif ($order->payment_status->value == 'partial')
        <x-primary-button wire:click='payRemaining'>{{ __('Pay Remaining Balance') }}</x-primary-button>
    @endif

    @unless ($order->cancellationRequest)
        @if ($canRequestCancellation)
            <x-danger-button wire:click='cancel'>{{ __('Request to Cancel') }}</x-danger-button>
        @endif
    @endunless

</div>
