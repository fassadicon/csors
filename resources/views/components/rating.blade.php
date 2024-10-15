@props(['starCount' => 5, 'fillStar' => 0])

<div class="flex space-x-1">
    @for ($i = 0; $i < $starCount; $i++) 
        @if ($i >= $fillStar) 
            <x-star />
        @else
            <x-star currentColor="#FFCA28" />
        @endif
    @endfor
</div>