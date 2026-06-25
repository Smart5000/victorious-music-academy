<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">Continue Learning</p>
            <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">All lessons you have started</h2>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($progressItems as $progress)
                    <a href="{{ route('lessons.show', $progress->lesson) }}" class="group block cursor-pointer rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-[#513CC7]/10 transition duration-300 hover:-translate-y-1 hover:shadow-xl focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]">
                        <div class="mb-5 overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
                            <div class="grid aspect-[16/9] place-items-center text-5xl text-[#513CC7] transition duration-300 group-hover:scale-105 group-focus-visible:scale-105">
                                {{ $progress->completed ? '✅' : '⭐' }}
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wide text-[#513CC7]">{{ $progress->lesson->course->instrument->title }}</p>
                                <h3 class="mt-2 text-xl font-black text-[#1C1F2F] transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">{{ $progress->lesson->title }}</h3>
                            </div>
                        </div>
                        <p class="mt-3 line-clamp-2 text-sm text-[#1C1F2F]/70">{{ $progress->lesson->description }}</p>
                        <x-progress-bar class="mt-5" :value="$progress->watched_percentage" label="Progress" />
                    </a>
                @empty
                    <div class="rounded-[2rem] bg-white p-8 text-center shadow-sm ring-1 ring-[#513CC7]/10 md:col-span-2 xl:col-span-3">
                        <p class="text-5xl">🎒</p>
                        <h3 class="mt-4 text-2xl font-black text-[#1C1F2F]">Your learning backpack is empty</h3>
                        <p class="mt-2 text-[#1C1F2F]/70">Start watching a lesson and it will appear here automatically.</p>
                        <a href="{{ route('academy.index') }}" class="mt-6 inline-flex rounded-full bg-[#513CC7] px-6 py-3 font-black text-white">Choose instruments</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $progressItems->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
