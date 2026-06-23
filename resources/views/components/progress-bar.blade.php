@props([
    'value' => 0,
    'label' => null,
])

@php
    $percent = max(0, min(100, (int) $value));
@endphp

<div {{ $attributes->class('space-y-2') }}>
    @if ($label)
        <div class="flex items-center justify-between text-sm font-bold text-[#1C1F2F]/75">
            <span>{{ $label }}</span>
            <span>{{ $percent }}%</span>
        </div>
    @endif

    <div class="h-3 overflow-hidden rounded-full bg-[#513CC7]/10">
        <div class="h-full rounded-full bg-[#513CC7] transition-all duration-500" style="width: {{ $percent }}%"></div>
    </div>
</div>
