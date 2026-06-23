@props(['active'])

@php
$classes = $active ?? false
            ? 'block w-full rounded-2xl bg-white px-4 py-3 text-start text-base font-bold text-[#513CC7] transition duration-150 ease-in-out focus:outline-none'
            : 'block w-full rounded-2xl px-4 py-3 text-start text-base font-bold text-white transition duration-150 ease-in-out hover:bg-white/10 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
