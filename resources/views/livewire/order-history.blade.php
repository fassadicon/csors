<div>
    @session('success')
        <x-mary-alert icon="o-banknotes"
            class="alert-success">
            {{ session('success') }}
        </x-mary-alert>
    @endsession
    <x-mary-header title="My Orders"
        class="!my-2">
    </x-mary-header>

    <x-mary-table :headers="$headers"
        :rows="$orders"
        striped class="!gap-y-4"
        show-empty-text
        empty-text="No orders found"
        {{-- @row-click="alert($event.detail.name)"  --}}
        {{-- :link="route('users.show', ['username' => ['username'], 'id' => ['id']])" --}}
        {{-- :cell-decoration="$cell_decoration" --}}>
        @scope('cell_total_amount', $order)
            {{ '₱ ' . $order->total_amount }}
        @endscope
        @scope('cell_payment_status', $order)
            <x-mary-badge :value="$order->payment_status->getLabel()"
                class="badge-{{ $order->payment_status->getMaryColor() }}" />
            {{-- class="badge-{{ $order->payment_status->getMaryColor() }}" --}}
        @endscope
        @scope('cell_order_status', $order)
            <x-mary-badge :value="$order->order_status->getLabel()"
                class="badge-{{ $order->order_status->getMaryColor() }}" />
        @endscope
        @scope('cell_start', $order)
            {{ \Carbon\Carbon::parse($order->start)->format('M d, Y g:i A') }}
        @endscope
        @scope('cell_end', $order)
            {{ \Carbon\Carbon::parse($order->start)->format('M d, Y g:i A') }}
        @endscope
        @scope('actions', $order)
            <a href="{{ route('view-order', ['order' => $order]) }}">
                <x-mary-button icon="o-eye"
                    spinner
                    class="btn-sm" />
            </a>
            @if ($order->order_status != 'completed')
                <x-mary-button icon="o-x-mark"
                    wire:click="requestCancellation({{ $order->id }})"
                    spinner
                    class="btn-sm btn-error" />
            @endif
            @if ($order->payment_status == 'partial')
                <x-mary-button icon="o-credit-card"
                    wire:click="payPartial({{ $order->id }})"
                    spinner
                    class="btn-sm" />
            @endif
        @endscope
    </x-mary-table>
</div>
