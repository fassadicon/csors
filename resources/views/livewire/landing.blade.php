<div  class="overflow-x-hidden">
    {{--
    <x-mary-header title="CSORS" class="mb-6" subtitle="Catering Service Ordering and Reservation System" separator /> --}}
    {{-- <h1>Add a touch of simple landing page design</h1> --}}

    <div style="background-image: url('{{ asset('images/bgs/landing-min.jpg') }}');" 
        class="absolute top-0 left-0 w-full h-screen bg-black bg-center bg-cover">
        {{-- OVERLAY  --}}
        <div class="absolute top-0 left-0 w-full h-screen text-white bg-black/70 ">
            {{-- MAIN CONTAINER - WIDTH CONSTRAINT  --}}
            <div class="flex flex-col-reverse items-center justify-center h-screen py-6 mx-auto md:flex-row md:items-center max-w-7xl sm:px-2 lg:px-4">
                {{-- CONTENT --}}
                <div class="space-y-2 w-[90%] sm:w-[70%] px-0 md:px-16 md:w-[70%] lg:w-[50] ">
                    <x-animate.transition-op class="duration-[1500ms]">
                        <h1 class="mb-4">Welcome to CSORS</h1>
                    </x-animate.transition-op>
    
                    <x-animate.transition-op class="duration-[2000ms]">
                        <h4>Delicious Catering Options for Every Occasion</h4>
                    </x-animate.transition-op>
    
                    <x-animate.transition-op class="duration-[2500ms]">
                        <p>At CSORS, we believe that great food brings people together. Whether you're planning a wedding,
                        corporate event, birthday party, or any special gathering, we have the perfect catering solutions tailored just
                        for you.</p>                
                    </x-animate.transition-op>
                    <x-animate.transition-op class="duration-[2500ms]">
                        <a href="{{ route('caterers') }}" >
                            <x-primary-button class="mt-4 btn-primary">{{ __('Browse Caterers') }}</x-primary-button>
                        </a>
                    </x-animate.transition-op>
                </div>    
                <x-animate.transition-op class="flex justify-center md:justify-end w-[40%] duration-1000">
                    <img src="{{asset('images/foods/plate/Food-Plate-Healthy-PNG.png')}}" alt="Food Image"
                        class="min-w-[250px] md:min-w-0 w-[100%]">
                </x-animate.transition-op>
            </div>
        </div>
    </div>
</div>
