<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="vvmi-eyebrow">Student Dashboard</p>
                <h2 class="text-3xl vvmi-heading">Welcome back, {{ auth()->user()->name }}</h2>
            </div>
            <a href="{{ route('academy.continue') }}" class="vvmi-button-primary">
                Continue learning
            </a>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="vvmi-container">
            @if (session('status'))
                <div class="mb-8 rounded-2xl bg-[#513CC7]/10 p-4 font-bold text-[#513CC7]">{{ session('status') }}</div>
            @endif

            <div class="grid items-start gap-8 md:grid-cols-[minmax(0,1fr)_280px] xl:grid-cols-[minmax(0,1fr)_320px]">
            <main class="space-y-10">
                <section class="vvmi-card-flat p-5 sm:p-7">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="vvmi-eyebrow">Recently watched</p>
                            <h3 class="mt-2 text-3xl vvmi-heading sm:text-4xl">Pick up where you stopped</h3>
                            <p class="mt-3 vvmi-body">Your latest lessons are here so you can jump back in fast.</p>
                        </div>
                        <a href="{{ route('academy.continue') }}" class="vvmi-button-ghost self-start sm:self-auto">View all</a>
                    </div>

                    <div class="mt-7 grid gap-5 md:grid-cols-2">
                        @forelse ($recentProgress as $progress)
                            <a href="{{ route('lessons.show', $progress->lesson) }}" class="group block cursor-pointer rounded-[1.75rem] border border-[#513CC7]/10 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]">
                                <div class="mb-5 overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
                                    <div class="grid aspect-[16/9] place-items-center text-5xl text-[#513CC7] transition duration-300 group-hover:scale-105 group-focus-visible:scale-105">
                                        {{ $progress->completed ? '✅' : '🎵' }}
                                    </div>
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-wide text-[#513CC7]">{{ $progress->lesson->course->instrument->title }}</p>
                                        <h4 class="mt-2 text-xl vvmi-heading transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">{{ $progress->lesson->title }}</h4>
                                    </div>
                                </div>

                                <p class="mt-3 line-clamp-2 text-sm vvmi-body">{{ $progress->lesson->description }}</p>
                                <x-progress-bar class="mt-5" :value="$progress->watched_percentage" label="Watched" />
                            </a>
                        @empty
                            <x-empty-state
                                class="md:col-span-2"
                                icon="🎵"
                                title="No lessons watched yet"
                                message="Start a course and your progress will appear here."
                                :action-url="route('academy.index')"
                                action-label="Browse instruments"
                            />
                        @endforelse
                    </div>
                </section>

                <section>
                    <x-section-heading
                        eyebrow="Recommended lessons"
                        title="Try these next"
                        description="A few friendly lessons to keep your practice moving."
                    />

                    <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        @forelse ($recommendedLessons as $lesson)
                            <a href="{{ route('lessons.show', $lesson) }}" class="group block cursor-pointer overflow-hidden rounded-[2rem] border border-[#513CC7]/10 bg-white p-4 shadow-[0_18px_60px_rgba(28,31,47,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_80px_rgba(28,31,47,0.12)] focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]">
                                <div class="overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
                                    <div class="grid aspect-[4/3] place-items-center text-5xl text-[#513CC7] transition duration-300 group-hover:scale-105 group-focus-visible:scale-105">🎬</div>
                                </div>
                                <h4 class="mt-4 font-black text-[#1C1F2F] transition duration-300 group-hover:text-[#513CC7] group-focus-visible:text-[#513CC7]">{{ $lesson->title }}</h4>
                                <p class="mt-2 text-sm text-[#1C1F2F]/70">{{ $lesson->course->instrument->title }} • {{ $lesson->course->title }}</p>
                            </a>
                        @empty
                            <x-loading-skeleton />
                        @endforelse
                    </div>
                </section>
            </main>

            <aside class="w-full space-y-4 md:sticky md:top-28 md:self-start">
                <div class="space-y-4 rounded-[1.75rem] border border-[#513CC7]/10 bg-white/70 p-3 shadow-[0_18px_60px_rgba(28,31,47,0.08)] backdrop-blur">
                <section class="rounded-[1.5rem] bg-[#513CC7] p-4 text-white shadow-xl shadow-[#513CC7]/15">
                    <div class="flex items-center gap-4">
                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-white/15 text-2xl">🎧</div>
                        <div>
                            <h3 class="text-base font-black leading-tight">Your music journey is growing</h3>
                            <p class="mt-1 text-sm leading-6 text-white/80">Small steps, steady progress.</p>
                        </div>
                    </div>
                    <x-progress-bar class="mt-5" :value="$overallProgress" label="Overall progress" />
                </section>

                <section class="rounded-[1.5rem] border border-[#513CC7]/10 bg-white p-4 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#513CC7]">Subscription</p>
                    @if ($activeSubscription)
                        <p class="mt-2 text-lg font-black text-[#1C1F2F]">{{ $activeSubscription->plan->name }}</p>
                        <p class="mt-1 text-sm font-semibold text-[#1C1F2F]/65">
                            Active{{ $activeSubscription->ends_at ? ' until '.$activeSubscription->ends_at->format('M j, Y') : '' }}
                        </p>
                    @else
                        <p class="mt-2 text-lg font-black text-[#1C1F2F]">Free access</p>
                        <p class="mt-1 text-sm font-semibold text-[#1C1F2F]/65">Subscribe to unlock premium lessons.</p>
                        <a href="{{ route('academy.index') }}" class="mt-3 inline-flex text-sm font-black text-[#513CC7] hover:underline">Browse instruments</a>
                    @endif
                </section>

                <section class="grid gap-4">
                    <div class="rounded-[1.5rem] border border-[#513CC7]/10 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="grid h-10 w-10 place-items-center rounded-2xl bg-[#513CC7]/10 text-xl">✅</div>
                            <div>
                                <p class="text-2xl font-black text-[#1C1F2F]">{{ $completedLessons }}</p>
                                <p class="text-sm font-bold text-[#1C1F2F]/70">Completed lessons</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-[#513CC7]/10 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="grid h-10 w-10 place-items-center rounded-2xl bg-[#513CC7]/10 text-xl">⭐</div>
                            <div>
                                <p class="text-2xl font-black text-[#1C1F2F]">{{ $recentProgress->count() }}</p>
                                <p class="text-sm font-bold text-[#1C1F2F]/70">Recently active lessons</p>
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </aside>
            </div>
        </div>
    </div>
</x-app-layout>
