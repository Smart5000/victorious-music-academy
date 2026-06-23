@props([
    'icon' => '🎵',
    'title',
    'message',
    'actionUrl' => null,
    'actionLabel' => null,
])

<div {{ $attributes->class('vvmi-card-flat p-8 text-center') }}>
    <div class="mx-auto vvmi-illustration h-20 w-20">{{ $icon }}</div>
    <h3 class="mt-5 text-2xl vvmi-heading">{{ $title }}</h3>
    <p class="mx-auto mt-2 max-w-md vvmi-body">{{ $message }}</p>

    @if ($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="vvmi-button-secondary mt-6">{{ $actionLabel }}</a>
    @endif
</div>
