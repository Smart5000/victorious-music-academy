@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    @if ($eyebrow)
        <p class="vvmi-eyebrow">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-2 text-3xl vvmi-heading sm:text-4xl">{{ $title }}</h2>
    @if ($description)
        <p class="mt-3 max-w-2xl vvmi-body">{{ $description }}</p>
    @endif
</div>
