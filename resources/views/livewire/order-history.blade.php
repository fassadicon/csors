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
        {{-- :cell-decoration="$cell_decoration" --}} />
</div>
