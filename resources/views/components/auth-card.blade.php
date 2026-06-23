<div class="min-h-screen bg-[#F8F6F2] px-4 py-8">
    <div class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-6xl items-center justify-center">
        <div class="grid w-full overflow-hidden rounded-[2.5rem] bg-white shadow-[0_30px_100px_rgba(28,31,47,0.12)] ring-1 ring-[#513CC7]/10 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="hidden bg-[#513CC7] p-10 text-white lg:block">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-black">
                    <span class="grid h-14 w-14 place-items-center rounded-full bg-white shadow-sm">
                        <span class="text-center font-display text-[9px] leading-none text-[#1C1F2F]">
                            <span class="block text-[#513CC7]">Victorious</span>
                            <span class="block">Music</span>
                        </span>
                    </span>
                    <span>Victorious Music Academy</span>
                </a>
                <div class="mt-16">
                    <p class="text-7xl">🎹🎸</p>
                    <h1 class="mt-8 font-display text-4xl tracking-tight">Music learning that feels like play.</h1>
                    <p class="mt-4 leading-7 text-white/80">A safe, friendly space for children to learn one joyful lesson at a time.</p>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <div class="mb-8 lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-black text-[#1C1F2F]">
                        <span class="grid h-12 w-12 place-items-center rounded-full bg-[#513CC7] text-xs text-white">VM</span>
                        <span>Victorious Music Academy</span>
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
