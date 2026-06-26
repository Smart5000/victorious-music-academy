@props(['course'])

@php
    $lessons = $course->lessons ?? collect();
    $progressValues = $lessons->flatMap->progress->where('user_id', auth()->id())->pluck('watched_percentage');
    $averageProgress = $progressValues->isNotEmpty() ? (int) round($progressValues->average()) : 0;
    $thumbnailUrl = \App\Support\Media::url($course->thumbnail_url, $course->thumbnail);
@endphp

<a
    href="{{ route('courses.show', $course) }}"
    {{ $attributes->class('group block h-full cursor-pointer overflow-hidden rounded-[2rem] border border-[#513CC7]/10 bg-white p-4 shadow-[0_18px_60px_rgba(28,31,47,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_80px_rgba(28,31,47,0.12)] focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]') }}
>
    <div class="overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
        @if ($thumbnailUrl)
            <img
                src="{{ $thumbnailUrl }}"
                alt="{{ $course->title }}"
                class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105 group-focus-visible:scale-105"
                loading="lazy"
            >
        @else
            <div class="grid aspect-[4/3] place-items-center bg-[#513CC7]/10 text-center text-[#513CC7]">
                <div>
                    <p class="text-5xl">🎵</p>
                    <p class="mt-3 text-xs font-black uppercase tracking-[0.2em]">{{ $course->category->name }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="pt-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="vvmi-eyebrow">{{ $course->category->name }}</p>
                <h3 class="mt-2 text-2xl vvmi-heading transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">{{ $course->title }}</h3>
            </div>
            <span class="rounded-2xl bg-[#513CC7]/10 px-3 py-2 text-sm font-black text-[#513CC7]">{{ $lessons->count() }} lessons</span>
        </div>

        <p class="mt-3 line-clamp-3 text-sm vvmi-body">{{ $course->description }}</p>
        <x-progress-bar class="mt-5" :value="$averageProgress" label="Course progress" />
    </div>
</a>
