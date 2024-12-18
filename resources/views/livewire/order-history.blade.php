<div>
    @session('success')
        <x-mary-alert icon="o-banknotes"
            class="alert-success">
            {{ session('success') }}
        </x-mary-alert>
    @endsession

    @session('warning')
        <x-mary-alert icon="o-banknotes"
            class="alert-warning">
            {{ session('warning') }}
        </x-mary-alert>
    @endsession
    <x-mary-header title="My Orders"
        class="!my-2">
    </x-mary-header>

    <x-mary-table :headers="$headers"
        :rows="$orders"
        striped
        hover
        class="!gap-y-4"
        {{-- show-empty-text --}}
        {{-- empty-text="No orders found" --}}
        {{-- @row-click="alert($event.detail.name)"  --}}
        {{-- :link="route('users.show', ['username' => ['username'], 'id' => ['id']])" --}}
        {{-- :cell-decoration="$cell_decoration" --}}>
        @scope('cell_final_amount', $order)
            {{ '₱ ' . number_format($order->final_amount, 2) }}
        @endscope

        @scope('cell_payment_status', $order)
            @php
                // Access the string value of the order status Enum
                $orderStatus = $order->order_status->value; // Get the value of the Enum
            @endphp
            @if (strtolower($orderStatus) === 'declined')
                <x-mary-badge value="Declined" style="background-color: red; color: white;"
                    class="badge-warning !bg-red-500 !text-white" />
            @else
                <x-mary-badge :value="$order->payment_status->getLabel()"
                    class="badge-{{ $order->payment_status->getMaryColor() }}" />
            @endif
        @endscope
        @scope('cell_order_status', $order)
            <x-mary-badge :value="$order->order_status->getLabel()"
                class="badge-{{ $order->order_status->getMaryColor() }}" />
        @endscope
        @scope('cell_duration', $order)
            {{ \Carbon\Carbon::parse($order->start)->format('M d, Y g:i A') . ' - ' . \Carbon\Carbon::parse($order->start)->format('M d, Y g:i A') }}
        @endscope

        {{-- @scope('cell_end', $order)
            {{ \Carbon\Carbon::parse($order->start)->format('M d, Y g:i A') }}
        @endscope --}}
        @scope('cell_created_at', $order)
            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y g:i A') }}
        @endscope
        @scope('actions', $order)
            <a href="{{ route('view-order', ['order' => $order]) }}">
                <x-mary-button icon="o-eye"
                    spinner
                    class="btn-sm" />
            </a>
            @if ($order->payment_status == 'partial')
                <x-mary-button icon="o-credit-card"
                    wire:click="payPartial({{ $order->id }})"
                    spinner
                    class="btn-sm" />
            @endif
        @endscope
    </x-mary-table>

    @if (count($orders) <= 0)
        <div class="flex items-center justify-center w-full mt-12">
            <div class="flex flex-col items-center gap-y-4">
                <img src="{{ asset('images/icons/no-order.png') }}"
                    alt="Empty Box"
                    class="w-[100px]">
                <p>No orders found.</p>
            </div>
        </div>
    @endif
</div>
