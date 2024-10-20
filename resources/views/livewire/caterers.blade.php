<div>
    <x-mary-header title="Caterers" class="!my-4"
        subtitle="Please select your chosen caterer to view their products, services, pricings, and more." separator />
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 !text-center">
        @foreach ($caterers as $caterer)
        <a href="{{ route('about', ['caterer' => $caterer]) }}">
            <x-mary-card title="{!! $caterer->name !!}" class="flex items-center justify-center">
                <div class="flex gap-x-2">
                    {{ \Illuminate\Support\Str::limit($caterer->about, 50) }}
                    <x-mary-badge value="+99" class="text-white badge-success" />
                </div>
                {{-- <x-mary-rating class="!my-4 badge-warning"/> --}}
                <div class="flex justify-center mt-2">
                    @if ($ratings[$caterer->id])
                        <x-rating :starCount="5" :fillStar="$ratings[$caterer->id] ?? 0" />
                    @else
                        <p >Not rated yet.</p>
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
    </div>
</div>