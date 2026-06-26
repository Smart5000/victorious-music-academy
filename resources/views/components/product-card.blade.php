@props(['product'])

@php
    $thumbnailUrl = \App\Support\Media::url($product->thumbnail_url, $product->thumbnail);
@endphp

<a
    href="{{ route('store.products.show', $product) }}"
    {{ $attributes->class('group block cursor-pointer focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]') }}
>
    <div class="h-56 overflow-hidden rounded-xl bg-[#513CC7]/10 shadow-sm transition duration-300 group-hover:-translate-y-1 group-hover:shadow-xl group-focus-visible:-translate-y-1 group-focus-visible:shadow-xl sm:h-60">
        @if ($thumbnailUrl)
            <img
                src="{{ $thumbnailUrl }}"
                alt="{{ $product->title }}"
                class="h-full w-full object-cover transition duration-300"
                loading="lazy"
            >
        @else
            <div class="grid h-full place-items-center bg-[#513CC7]/10 text-center text-[#513CC7] transition duration-300 group-hover:scale-105 group-focus-visible:scale-105">
                <div>
                    <p class="text-5xl">🎼</p>
                    <p class="mt-3 text-xs font-black uppercase tracking-[0.2em]">{{ $product->category->name }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="pt-4">
        <h3 class="text-xl font-black leading-snug text-[#343b8c] transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">
            {{ $product->title }}
        </h3>
        @if ($product->description)
            <p class="mt-1 line-clamp-2 text-sm font-semibold leading-6 text-[#1C1F2F]/70">{{ $product->description }}</p>
        @endif
        <p class="mt-1 text-lg font-black text-[#1C1F2F]">{{ $product->price_label }}</p>
    </div>
</a>
