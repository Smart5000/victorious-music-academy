<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">{{ $course->instrument->title }} • {{ $course->category->name }}</p>
                <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">{{ $course->title }}</h2>
            </div>
            @if ($course->lessons->first())
                <a href="{{ route('lessons.show', $course->lessons->first()) }}" class="inline-flex rounded-full bg-[#513CC7] px-5 py-3 text-sm font-black text-white">
                    Start course
                </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8">
            <aside class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-[#513CC7]/10">
                <p class="text-5xl">{{ $course->instrument->title === 'Keyboard' ? '🎹' : '🎸' }}</p>
                <h3 class="mt-6 text-2xl font-black text-[#1C1F2F]">Course overview</h3>
                <p class="mt-3 leading-7 text-[#1C1F2F]/70">{{ $course->description }}</p>
                <div class="mt-8 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-[#513CC7]/5 p-4">
                        <p class="text-2xl font-black">{{ $course->lessons->count() }}</p>
                        <p class="text-sm text-[#1C1F2F]/70">Lessons</p>
                    </div>
                    <div class="rounded-2xl bg-[#513CC7]/5 p-4">
                        <p class="text-2xl font-black">{{ $course->category->name }}</p>
                        <p class="text-sm text-[#1C1F2F]/70">Level</p>
                    </div>
                </div>
            </aside>

            <main class="space-y-4">
                @forelse ($course->lessons as $lesson)
                    <x-lesson-row :lesson="$lesson" />
                @empty
                    <div class="rounded-[2rem] bg-white p-8 text-center shadow-sm ring-1 ring-[#513CC7]/10">
                        <p class="text-5xl">🎬</p>
                        <h3 class="mt-4 text-2xl font-black text-[#1C1F2F]">No lessons yet</h3>
                        <p class="mt-2 text-[#1C1F2F]/70">The admin can add lessons for this course in Filament.</p>
                    </div>
                @endforelse
            </main>
        </div>
    </div>
</x-app-layout>
