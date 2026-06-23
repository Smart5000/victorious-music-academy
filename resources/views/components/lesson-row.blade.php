@props([
    'lesson',
    'active' => false,
])

@php
    $progress = $lesson->progress->firstWhere('user_id', auth()->id());
    $percent = $progress?->watched_percentage ?? 0;
@endphp

<a href="{{ route('lessons.show', $lesson) }}" {{ $attributes->class([
    'block rounded-2xl p-4 transition duration-300',
    'bg-[#513CC7]/10 text-[#1C1F2F] ring-2 ring-[#513CC7]/25' => $active,
    'bg-white text-[#1C1F2F]/75 ring-1 ring-[#513CC7]/10 hover:-translate-y-0.5 hover:bg-[#513CC7]/5' => ! $active,
]) }}>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-xs font-black uppercase tracking-wide text-[#513CC7]">Lesson {{ $lesson->lesson_order }}</p>
            <h4 class="mt-1 font-black">{{ $lesson->title }}</h4>
        </div>
        <span class="text-lg">{{ $percent >= 100 ? '✅' : ($percent >= 50 ? '⭐' : '○') }}</span>
    </div>
    <x-progress-bar class="mt-3" :value="$percent" />
</a>
