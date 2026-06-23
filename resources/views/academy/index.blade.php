<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">Instruments</p>
                <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">Choose your musical adventure</h2>
            </div>
            <a href="{{ route('academy.continue') }}" class="inline-flex rounded-full bg-[#513CC7] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#513CC7]/20">
                Continue learning
            </a>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 350)">
                <div x-show="loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <x-loading-skeleton />
                    <x-loading-skeleton />
                    <x-loading-skeleton />
                    <x-loading-skeleton />
                </div>

                <div x-show="! loading" x-cloak class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($instruments as $instrument)
                        <x-instrument-card :instrument="$instrument" />
                    @endforeach
                </div>
            </div>

            <div class="mt-12 rounded-[2rem] bg-[#513CC7] p-8 text-white shadow-xl shadow-[#513CC7]/20">
                <p class="text-4xl">🎼</p>
                <h3 class="mt-5 text-2xl font-black">How lessons are arranged</h3>
                <p class="mt-3 max-w-3xl text-white/80">Each instrument is grouped into Beginner, Intermediate, and Advanced courses. Lessons are short, ordered, and designed for children to build confidence step by step.</p>
            </div>
        </div>
    </div>
</x-app-layout>
