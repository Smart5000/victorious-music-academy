<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">Instrument Preview</p>
            <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">{{ $instrument->title }} courses</h2>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ showPlans: false }">
            @if (session('status'))
                <div class="mb-8 rounded-2xl bg-[#513CC7]/10 p-4 font-bold text-[#513CC7]">{{ session('status') }}</div>
            @endif

            <section class="rounded-[2rem] bg-white p-6 shadow-[0_18px_60px_rgba(28,31,47,0.08)] ring-1 ring-[#513CC7]/10 sm:p-8">
                <div class="grid gap-6 lg:grid-cols-[17px_1fr_auto] lg:items-center">
                    <div class="w-full max-w-[17px] overflow-hidden rounded-[1.5rem] bg-[#513CC7]/10">
                        @if ($instrument->thumbnail)
                            <img
                                src="{{ asset('storage/'.$instrument->thumbnail) }}"
                                alt="{{ $instrument->title }}"
                                class="h-[12px] w-[17px] object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="grid h-[12px] w-[17px] place-items-center text-[#513CC7]">
                                <span class="text-6xl">♪</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p class="vvmi-eyebrow">{{ $instrument->coming_soon ? 'Coming Soon' : 'Ready to Learn' }}</p>
                        <h3 class="mt-2 text-4xl vvmi-heading">{{ $instrument->title }}</h3>
                        <p class="mt-4 max-w-3xl vvmi-body">
                            {{ $instrument->description ?: 'Explore structured, child-friendly courses designed to help young learners grow step by step.' }}
                        </p>
                    </div>

                    <div class="lg:text-right">
                        @if ($activeSubscription)
                            <a href="{{ route('dashboard') }}" class="vvmi-button-primary">Go to dashboard</a>
                            <p class="mt-3 text-sm font-bold text-[#1C1F2F]/60">Your subscription is active.</p>
                        @else
                            <button type="button" class="vvmi-button-primary" x-on:click="showPlans = true; $nextTick(() => $refs.plans?.scrollIntoView({ behavior: 'smooth', block: 'start' }))">
                                Subscribe
                            </button>
                            <p class="mt-3 text-sm font-bold text-[#1C1F2F]/60">Subscribe to unlock the full learning path.</p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="mt-10 rounded-[2rem] bg-white p-6 shadow-[0_18px_60px_rgba(28,31,47,0.08)] ring-1 ring-[#513CC7]/10 sm:p-8">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="vvmi-eyebrow">Course Preview</p>
                        <h3 class="mt-2 text-3xl vvmi-heading">What you will learn</h3>
                    </div>
                    <span class="rounded-full bg-[#513CC7]/10 px-4 py-2 text-sm font-black text-[#513CC7]">
                        {{ $instrument->courses->count() }} {{ str('course')->plural($instrument->courses->count()) }}
                    </span>
                </div>

                @if ($instrument->courses->isNotEmpty())
                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($instrument->courses->sortBy('order')->chunk(10) as $courseColumn)
                            <div class="space-y-3">
                                @foreach ($courseColumn as $course)
                                    @if ($activeSubscription)
                                        <a
                                            href="{{ route('courses.show', $course) }}"
                                            class="block rounded-2xl border border-[#513CC7]/10 bg-[#F8F6F2] p-4 transition duration-200 hover:-translate-y-0.5 hover:border-[#513CC7]/30 hover:bg-[#513CC7]/5 focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-4 focus-visible:outline-[#513CC7]"
                                        >
                                            <div class="flex items-start justify-between gap-4">
                                                <p class="font-black text-[#1C1F2F]">{{ $course->title }}</p>
                                                <span class="shrink-0 text-sm font-black text-[#513CC7]">{{ $course->lessons_count }} lessons</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="rounded-2xl border border-[#513CC7]/10 bg-[#F8F6F2] p-4">
                                            <div class="flex items-start justify-between gap-4">
                                                <p class="font-black text-[#1C1F2F]">{{ $course->title }}</p>
                                                <span class="shrink-0 text-sm font-black text-[#513CC7]">{{ $course->lessons_count }} lessons</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-[2rem] bg-[#F8F6F2] p-8 text-center">
                        <p class="text-5xl">♪</p>
                        <h4 class="mt-4 text-2xl vvmi-heading">Courses are being tuned up</h4>
                        <p class="mt-2 vvmi-body">Please check back soon for this instrument’s curriculum.</p>
                    </div>
                @endif
            </section>

            @unless ($activeSubscription)
                <section class="mt-10" x-ref="plans" x-show="showPlans" x-cloak>
                    <div class="mb-6">
                        <p class="vvmi-eyebrow">Premium Learning</p>
                        <h3 class="mt-2 text-3xl vvmi-heading">Choose your learning plan</h3>
                    </div>

                    @include('subscriptions.partials.plans', [
                        'plans' => $plans,
                        'activeSubscription' => $activeSubscription,
                        'selectedInstrument' => $instrument,
                    ])
                </section>
            @endunless
        </div>
    </div>
</x-app-layout>
