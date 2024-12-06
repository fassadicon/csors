<div>
    <x-mary-header title="Caterers" class="!my-4"
        subtitle="Please select your chosen caterer to view their products, services, pricings, and more." separator />
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 !text-center ">
        {{-- @dd()) --}}
        @foreach ($caterers as $caterer)
        <a wire:click="selectCaterer({{ $caterer }})">
            <x-mary-card title="{!! $caterer->name !!}" class="flex items-center justify-center cursor-pointer">
                <div class="flex items-center justify-center gap-x-2 ">
                    {!! \Illuminate\Support\Str::limit($caterer->about, 50) !!}

                </div>
                {{--
                <x-mary-rating class="!my-4 badge-warning" /> --}}
                <div class="flex items-center justify-center mt-2 gap-x-2">
                    @if ($ratings[$caterer->id])
                    <x-rating :starCount="5" :fillStar="$ratings[$caterer->id] ?? 0" />
                    <x-mary-badge value="+{{ $numOfRating[$caterer->id]     }}" class="text-white badge-success" />
                    @else
                    <p>Not rated yet.</p>
                    <x-mary-badge value="0" class="text-white badge-success" />
                    @endif
                </div>
                <x-slot:figure>
                    {{-- Change this later to get image --}}
                    @if ($caterer->logo_path)
                    <img src="{{ asset('storage/'.$caterer->logo_path) }}" class="card-img" />
                    @else
                    <img src="{{ asset('images/placeholder.jpg') }}" class="card-img" />
                    @endif
                </x-slot:figure>
            </x-mary-card>
        </a>
        @endforeach
        @if (count($caterers) <= 0)
            <p >No caterers yet.</p>
        @endif
    </div>
</div>
