<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">{{ $instrument->title }}</p>
            <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">Courses for young {{ $instrument->title }} learners</h2>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-8 rounded-2xl bg-[#513CC7]/10 p-4 font-bold text-[#513CC7]">{{ session('status') }}</div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                @forelse ($instrument->courses->sortBy('order') as $course)
                    <x-course-card :course="$course" />
                @empty
                    <div class="rounded-[2rem] bg-white p-8 text-center shadow-sm ring-1 ring-[#513CC7]/10 lg:col-span-3">
                        <p class="text-5xl">🎵</p>
                        <h3 class="mt-4 text-2xl font-black text-[#1C1F2F]">Courses are being tuned up</h3>
                        <p class="mt-2 text-[#1C1F2F]/70">Please check back soon for this instrument’s curriculum.</p>
                    </div>
                @endforelse
            </div>

            <section class="mt-14">
                <div class="mb-6">
                    <p class="vvmi-eyebrow">Premium Learning</p>
                    <h3 class="mt-2 text-3xl vvmi-heading">Choose your learning plan</h3>
                </div>

                @include('subscriptions.partials.plans', compact('plans', 'activeSubscription'))
            </section>
        </div>
    </div>
</x-app-layout>
