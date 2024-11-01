@php
    $user = auth()->user();
    // dd($user);
@endphp
<script src="https://cdn.tailwindcss.com"></script>
<div class="fixed top-0 left-0 flex flex-col items-center justify-center w-full h-screen md:items-start gap-y-4">
    <img draggable="false" src="{{asset('images/bgs/bg1.jpg')}}" alt=""
        class="absolute z-0 object-cover w-full h-screen overlay">
    {{-- overlay --}}
    {{-- <div class="absolute w-full h-screen bg-white/25"></div> --}}
    <!-- Session Status -->
    <div
        class="z-10 px-8 md:px-16 py-8 ml-0 sm:ml-4 bg-white md:ml-16 rounded-md md:min-w-[450px] w-[90%] md:w-[30%] space-y-2 ">
        <div class="flex flex-row items-center justify-center md:justify-start ">
            <div class="inline-flex w-[10px] h-[40px] bg-jt-primary"></div>
            <h3 class="ml-2 font-bold">Verify you account</h3>
        </div>
        <p class="text-center md:text-left">You need to verify your account before you can place an order / reservations.</p>
        @if (session('error'))
        <div class="text-red-500">{{ session('error') }}</div>
        @endif
        
        @if (session('message'))
        <div class="text-green-500">{{ session('message') }}</div>
        @endif
    </div>
    <form method="POST" action="validate-otp"
        class=" z-10 px-16 py-8 ml-0 sm:ml-4 md:ml-16 rounded-md bg-jt-white w-[90%] md:min-w-[450px] md:w-[30%]">
        @csrf
        <div>
            <x-input-label class="!text-black" for="otp" :value="__('OTP')" />
            <x-text-input wire:model="otp" id="otp" maxlength='4' class="block w-full mt-1 px-4 !text-black py-4 !bg-white" type="text" name="otp"
                required autofocus  />
            {{-- <x-input-error :messages="$errors->get('otp')" class="mt-2" /> --}}
            @error('otp')
                <p class="text-sm italic text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col-reverse items-center justify-end mt-4 gap-y-4 md:gap-y-0 md:flex-row">
            <x-primary-button class="ms-3 btn-primary">
                {{ __('Verify') }}
            </x-primary-button>
            <br>
        </div>
        <a href="{{ route('request-otp') }}">Request new OTP</a>
        <hr class="my-4">
    </form>
    
</div>