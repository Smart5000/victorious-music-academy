@props(['quote', 'name', 'role'])

<figure {{ $attributes->class('vvmi-card-flat p-6') }}>
    <div class="vvmi-illustration h-14 w-14 text-3xl">🌟</div>
    <blockquote class="mt-4 text-sm vvmi-body">“{{ $quote }}”</blockquote>
    <figcaption class="mt-5">
        <p class="font-black text-[#1C1F2F]">{{ $name }}</p>
        <p class="text-sm text-[#1C1F2F]/70">{{ $role }}</p>
    </figcaption>
</figure>
