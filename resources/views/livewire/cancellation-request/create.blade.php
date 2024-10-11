<div class="p-4 bg-jt-white rounded-md">
    <x-mary-header title="Request Cancellation"
        class="!my-2 flex justify-center"
        separator />
    <hr class="my-2 mx-4">
    <div class="flex justify-center items-center p-4">
        <div class="flex flex-col items-center justify-center text-center w-[90%] md:w-[80%]">
            <img src="{{asset('images/icons/cancel.png')}}" alt="Cancel Icon" class="w-[150px]">
            <h3>We're Sorry to See You Go</h3>
            <p>We’re sorry to hear that you’ve decided to cancel your order. We understand that things can change, and we're here to
                help make the process as smooth as possible.</p>
            <p>
                Thank you for considering us, and we hope to serve you again in the future!
            </p>
        </div>
    </div>
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
            class=" text-white w-full bg-red-500 hover:bg-red-700"
            spinner />
    </form>
</div>
