<div class="p-4 bg-jt-white rounded-md">
    <div class="flex justify-between px-4">
        <x-mary-header title="Order Information"
            {{-- subtitle="# {{ $order->id }} - PHP {{ $order->total_amount }}" --}}
            class="!my-4 ">
        </x-mary-header>

        <div class="mt-4 flex-col flex justify-end gap-y-4">
            <div class="flex flex-row gap-x-2 justify-end">
                <x-mary-badge value="Payment: {{ $order->payment_status->getLabel() }}"
                    class="badge-{{ $order->payment_status->getMaryColor() }}" />
                <x-mary-badge value="Order: {{ $order->order_status->getLabel() }}"
                    class="badge-{{ $order->order_status->getMaryColor() }}" />
            </div>
            <h3>#{{ $order->id }} - Total Php {{ number_format($order->total_amount, 2) }}</h3>
        </div>
    </div>

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
                    PHP {{ number_format($orderItem->amount, 2) }}
                </x-slot:actions>
            </x-mary-list-item>
        </div>
    @endforeach
    {{-- <x-mary-header title="Total: {{ $totalAmount }}"
        class="!my-2"
        separator /> --}}
    <div class="flex justify-between items-end">
        <div>
            <x-mary-header title="Customer Information"
                class="!my-2"
                subtitle="Customer: {{ $order->user->name }}"
                separator />
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">

            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Caterer: {{ $order->caterer->name }}
            </p>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Start: {{ $order->start->format('M j, Y - g:ia') }}
                {{-- - {{ $order->start->diffForHumans() --}}
            </p>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                End: {{ $order->end->format('M j, Y - g:ia') }}
            </p>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Location: {{ $order->location }}
            </p>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Remarks: {{ $order->remarks }}
            </p>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Date Ordered: {{ $order->created_at->format('M j, Y - g:ia') }}
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
        </div>

        <div class="flex flex-col gap-y-2 p-4 md:p-0 w-[90%] md:w-[45%] mt-4">

            @if ($canPay)
                <div class="flex gap-x-2">
                    @if ($order->payment_status->value == 'pending')
                        <x-primary-button class="w-full bg-jt-primary-dark flex !justify-center !text-center"
                            wire:click='payPartial'>{{ __('Pay Partial') }}</x-primary-button>
                        <x-primary-button class="w-full btn-primary flex !justify-center"
                            wire:click='payFull'>{{ __('Pay Full') }}
                        </x-primary-button>
                    @elseif ($order->payment_status->value == 'partial')
                        <x-primary-button class="w-full btn-primary flex !justify-center"
                            wire:click='payRemaining'>{{ __('Pay Remaining Balance') }}</x-primary-button>
                    @endif
                </div>
            @endif

            {{-- CANCEL --}}
            @unless ($order->cancellationRequest)
                @if ($canRequestCancellation)
                    <x-danger-button class="flex justify-center bg-slate-900"
                        wire:click='cancel'>{{ __('Request to Cancel') }}
                    </x-danger-button>
                @endif
            @endunless
            <a href="{{ route('order-history') }}">
                <x-mary-button label="Back to Order History"
                    class=" w-full py-2 btn-outline" />
            </a>
        </div>
    </div>
</div>
