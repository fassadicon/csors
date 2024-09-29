<div>
    <x-mary-header title="Contact us"
        class="!my-2"
        size='text-xl'>
    </x-mary-header>

    <x-mary-form wire:submit="send">
        <x-mary-input label="Name"
            wire:model="name" />
        <x-mary-input label="Subject"
            wire:model="subject" />
        <x-mary-input label="Email"
            wire:model="email" />
        <x-mary-textarea label="Message"
            wire:model="content"
            placeholder="I want to discuss options..."
            rows="5" />

        <x-slot:actions>
            <x-mary-button label="Cancel" />
            <x-mary-button label="Send"
                class="btn-primary"
                type="submit"
                spinner='send' />
        </x-slot:actions>
    </x-mary-form>
</div>
