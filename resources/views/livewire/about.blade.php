<div>
    <x-mary-header title="{{ $caterer->name }}"
        class="mb-6"
        subtitle="By {{ $caterer->user->name }}"
        separator />
    <x-mary-button label="Select Caterer"
        class="btn-primary"
        wire:click="select" />
    {!! $caterer->about !!}
</div>
