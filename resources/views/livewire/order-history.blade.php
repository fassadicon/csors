<div>
    <x-mary-header title="My Orders"
        class="!my-2">
    </x-mary-header>

    <x-mary-table :headers="$headers"
        :rows="$orders"
        striped
        show-empty-text
        empty-text="No orders found"
        {{-- @row-click="alert($event.detail.name)"  --}}
        {{-- :link="route('users.show', ['username' => ['username'], 'id' => ['id']])" --}}
        {{-- :cell-decoration="$cell_decoration" --}}>
        @scope('start', $order)
            {{ \Carbon\Carbon::parse($order->start)->format('F d, Y') }}
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
                    class="btn-sm" />
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
