<div  class="">
    {{--
    <x-mary-header title="CSORS" class="mb-6" subtitle="Catering Service Ordering and Reservation System" separator /> --}}
    
    
    
    {{-- <h1>Add a touch of simple landing page design</h1> --}}
    <div style="background-image: url('{{ asset('images/bgs/landing-min.jpg') }}');" 
        class="absolute top-0 left-0 w-full h-screen bg-black bg-center bg-cover">
        {{-- OVERLAY  --}}
        <div class="absolute top-0 left-0 w-full h-screen text-white bg-black/70 ">
            {{-- MAIN CONTAINER - WIDTH CONSTRAINT  --}}
            <div class="flex flex-col items-center justify-center h-full py-6 mx-auto md:items-start max-w-7xl sm:px-2 lg:px-4">
                {{-- CONTENT --}}
                <div class="space-y-2 w-[90%] sm:w-[70%] px-0 md:px-16 md:w-[70%] lg:w-[50] ">
                    <h1 class="mb-4">Welcome to CSORS</h1>
                    <h4>Delicious Catering Options for Every Occasion</h4>
                    <p>At CSORS, we believe that great food brings people together. Whether you're planning a wedding,
                        corporate event, birthday party, or any special gathering, we have the perfect catering solutions tailored just
                        for you.</p>
                    
                    <a href="{{ route('caterers') }}" >
                        <x-primary-button class="mt-4 btn-primary">{{ __('Browse Caterers') }}</x-primary-button>
                    </a>
                </div>    
            </div>
        </div>
    </div>
</div>
