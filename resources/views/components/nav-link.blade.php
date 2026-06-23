@props(['active'])

@php
$classes = $active ?? false
            ? 'inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-bold text-[#513CC7] transition duration-200 focus:outline-none'
            : 'inline-flex items-center rounded-full px-4 py-2.5 text-sm font-bold text-white transition duration-200 hover:bg-white/10 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
