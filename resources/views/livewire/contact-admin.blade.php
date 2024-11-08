<div class="p-4 shadow-md shadow-black/5 bg-jt-light">
    <x-mary-header title="Phone number" class="!my-2 !mb-4" size='text-2xl'
        subtitle="{{ session('admin_phone_number') }}">
    </x-mary-header>
    sadasdsa
    <x-mary-header title="Contact Admin" class="!my-2" size='text-xl'>
    </x-mary-header>

    <x-mary-form wire:submit="send">
        <x-mary-input label="Name" class="!border-black/25" wire:model="name" required />
        <x-mary-input label="Subject" class="!border-black/25" wire:model="subject" required />
        <x-mary-input label="Email" class="!border-black/25" wire:model="email" required />
        <x-mary-textarea label="Message" class="!border-black/25" wire:model="content"
            placeholder="I want to discuss options..." rows="5" required />

        <x-slot:actions>
            <x-mary-button label="Cancel" />
            <x-mary-button label="Send" class="btn-primary" type="submit" spinner='send' />
        </x-slot:actions>
    </x-mary-form>
</div>