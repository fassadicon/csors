<div x-data="{
                submitFeedback: false,
                submitReport: false,
                tabOpen: '',

                showFeedback() {
                    this.tabOpen = 'feedbackPop';
                },
                showReport() {
                    this.tabOpen = 'reportPop';
                },
            }"  
            x-init="setTimeout(() => tabOpen = 'review', 1000)"
            x-on:feedback.window="showFeedback()"
            x-on:report.window="showReport()"
    class="overflow-x-hidden flex justify-center items-center text-center fixed left-0 top-0 z-[100] bg-black/50 w-full
    h-screen">
    {{-- x-init="setTimeout(() => tabOpen = 'review', 1000)" --}}
    <!-- Feedback Form -->
    <template x-if="tabOpen == 'review'" >
        <form wire:submit='sendFeedback'
            x-transition:enter="transition-all ease-out duration-[1000ms]"
            x-transition:enter-start="opacity-0 transition-x-10 scale-90"
            x-transition:enter-end="opacity-100 transition-x-0 scale-100"
            x-transition:leave="transition-all ease-in duration-300" 
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90" 
            x-show="tabOpen == 'review'" 
            class="min-w-[250px] flex flex-col items-center w-[90%] md:w-[40%] justify-center p-4 bg-white rounded-md gap-y-4">
            <h5>Order ID: {{$order->id}}</h5>
            <h3>{{$order->caterer->name}}</h3>
            <x-mary-header title="Congratulations on your last event!"
                subtitle="What do you think of the service of the caterer?" class="!my-4 !mt-8"></x-mary-header>
            <x-mary-rating wire:model="rate" class="-mt-4 bg-amber-400 focus:!bg-amber-400 !text-amber-400" required/>
            @error('rate')
                <p class="text-sm italic text-red-500">{{$message}}</p>
            @enderror
            <x-mary-textarea wire:model="comment" placeholder="Your feedback..." hint="Max 1000 chars" rows="3" inline
                class="min-w-[250px]" />
            <button type="submit" class="px-4 py-2 btn-primary">SEND FEEDBACK</button>
            <div>or</div>
            <div @click="tabOpen = 'report'" class="cursor-pointer hover:text-blue-500 hover:font-bold">Report Caterer</div>
        </form>
    </template>
    <!-- Report Form -->
    <template x-if="tabOpen == 'report'">
        <form wire:submit='sendReport'
            x-transition:enter="transition-all ease-out duration-[1000ms]"
            x-transition:enter-start="opacity-0 transition-x-10 scale-90"
            x-transition:enter-end="opacity-100 transition-x-0 scale-100"
            x-transition:leave="transition-all ease-in duration-300" 
            x-transition:leave-start="opacity-100 scale-100"
            class="flex flex-col items-center w-[90%] md:w-[40%] justify-center p-4 px-2 min-w-[250px] md:px-10 bg-white rounded-md gap-y-4">
            
            <h5>Order ID: {{$order->id}}</h5>
            <h3>{{$order->caterer->name}}</h3>
            
            <x-mary-header title="We’re Sorry for the Inconvenience!"
                subtitle="At CSORS, we strive to provide the best service possible. If your experience didn’t meet your expectations, please let us know. Your feedback is invaluable in helping us improve our offerings and ensure a delightful experience for all our clients."
                class="!my-4 !mt-8"></x-mary-header>
            
            <x-mary-textarea wire:model="comment" placeholder="Your feedback..." hint="Max 1000 chars" rows="3" inline
                class="min-w-[250px]" />
            <button type="submit" class="px-4 py-2 text-white bg-red-500">SEND REPORT</button>
            <div>or</div>
            <div @click="tabOpen = 'review'" class="cursor-pointer hover:text-blue-500 hover:font-bold">Back</div>
        </form>
    </template>

    <template x-if="tabOpen == 'feedbackPop'">
        <form wire:submit='reload' x-transition:enter="transition-all ease-out duration-[1000ms]"
            x-transition:enter-start="opacity-0 transition-x-10 scale-90"
            x-transition:enter-end="opacity-100 transition-x-0 scale-100"
            x-transition:leave="transition-all ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            class="flex flex-col items-center w-[90%] md:w-[40%] justify-center p-4 px-2 min-w-[250px] md:px-10 bg-white rounded-md gap-y-4">
            <x-mary-header title="Thank you for your feedback!"
                class="!my-4 !mt-8"></x-mary-header>
            <button type="submit" class="px-4 py-2 text-white bg-red-500">CLOSE</button>
        </form>
    </template>

    <template x-if="tabOpen == 'reportPop'">
        <form wire:submit='reload' x-transition:enter="transition-all ease-out duration-[1000ms]"
            x-transition:enter-start="opacity-0 transition-x-10 scale-90"
            x-transition:enter-end="opacity-100 transition-x-0 scale-100"
            x-transition:leave="transition-all ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            class="flex flex-col items-center w-[90%] md:w-[40%] justify-center p-4 px-2 min-w-[250px] md:px-10 bg-white rounded-md gap-y-4">
            <x-mary-header title="Thank you for your feedback!" class="!my-4 !mt-8"></x-mary-header>
            <button type="submit" class="px-4 py-2 text-white bg-red-500">CLOSE</button>
        </form>
    </template>

</div>
