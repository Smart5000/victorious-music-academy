@props(['instrument'])

@php
    $emoji = match ($instrument->title) {
        'Keyboard' => '🎹',
        'Guitar' => '🎸',
        'Violin' => '🎻',
        'Drums' => '🥁',
        default => '🎵',
    };

    $cardClasses = 'group block h-full overflow-hidden rounded-[2rem] border border-[#513CC7]/10 bg-white p-4 shadow-[0_18px_60px_rgba(28,31,47,0.08)] transition duration-300';
    $interactiveClasses = 'cursor-pointer hover:-translate-y-1 hover:shadow-[0_24px_80px_rgba(28,31,47,0.12)] focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]';
    $thumbnailUrl = \App\Support\Media::url($instrument->thumbnail_url, $instrument->thumbnail);
@endphp

@if ($instrument->coming_soon)
    <article {{ $attributes->class($cardClasses) }}>
@else
    <a href="{{ route('academy.instrument', $instrument) }}" {{ $attributes->class($cardClasses.' '.$interactiveClasses) }}>
@endif
    <div class="overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
        @if ($thumbnailUrl)
            <img
                src="{{ $thumbnailUrl }}"
                alt="{{ $instrument->title }}"
                class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105 group-focus-visible:scale-105"
                loading="lazy"
            >
        @else
            <div class="grid aspect-[4/3] place-items-center bg-[#513CC7]/10 text-center text-[#513CC7]">
                <div>
                    <p class="text-6xl">{{ $emoji }}</p>
                    <p class="mt-3 text-xs font-black uppercase tracking-[0.2em]">{{ $instrument->coming_soon ? 'Coming Soon' : 'Start Learning' }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="pt-5">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-2xl vvmi-heading transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">{{ $instrument->title }}</h3>
            @if ($instrument->coming_soon)
                <span class="shrink-0 rounded-full bg-[#513CC7]/10 px-3 py-2 text-sm font-black text-[#513CC7]">Coming Soon</span>
            @endif
        </div>
        <p class="mt-2 line-clamp-3 text-sm vvmi-body">{{ $instrument->description }}</p>
    </div>
@if ($instrument->coming_soon)
    </article>
@else
    </a>
@endif
