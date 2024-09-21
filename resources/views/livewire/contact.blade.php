<div>
    <x-mary-header title="Contact us"
        class="!my-2">
    </x-mary-header>

    <x-mary-form wire:submit="save">
        <x-mary-input label="Name"
            wire:model="name" />
        <x-mary-input label="Email"
            wire:model="customerEmail" />
        <x-mary-textarea label="Message"
            wire:model="content"
            placeholder="I want to discuss options..."
            rows="5" />

        <x-slot:actions>
            <x-mary-button label="Cancel" />
            <x-mary-button label="Click me!"
                class="btn-primary"
                type="submit"
                spinner="save" />
        </x-slot:actions>
    </x-mary-form>
</div>
