<div>
    <x-mary-header title="Request Cancellation"
        class="!my-2"
        separator />

    <h1>Inser Order Summary (Smaller than view order)</h1>

    <form wire:submit='submitCancellation'
        class="p-4 rounded-sm bg-jt-white">
        <div class="space-y-2">
            <x-mary-textarea label="Reason for Cancellation"
                wire:model="reason"
                placeholder="Notes for the Caterer"
                rows="4"
                inline />
        </div>
        <hr class="my-4">
        <x-mary-button type="submit"
            label="Submit Cancellation Request"
            class="btn-danger"
            spinner />
    </form>
</div>
