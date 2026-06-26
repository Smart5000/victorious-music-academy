<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victorious Music Academy</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @include('partials.vite-fallback')
    @endif
</head>
<body class="bg-[#F8F6F2] font-sans text-[#1C1F2F]">
    <div class="vvmi-doodle-bg min-h-screen overflow-hidden bg-[#F8F6F2]">
        <nav class="bg-[#513CC7] text-white shadow-sm">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-14 w-14 place-items-center rounded-full bg-white shadow-sm ring-1 ring-white/40">
                    <span class="text-center font-display text-[9px] leading-none text-[#1C1F2F]">
                        <span class="block text-[#513CC7]">Victorious</span>
                        <span class="block">Music</span>
                    </span>
                </span>
                <span class="sr-only">Victorious Music Academy</span>
            </a>
            <div class="flex items-center gap-4 text-sm font-extrabold">
                <a href="{{ route('store.index') }}" class="rounded-full px-4 py-2 text-white/90 transition hover:bg-white/10 hover:text-white">Store</a>
                <a href="{{ route('subscriptions.index') }}" class="rounded-full px-4 py-2 text-white/90 transition hover:bg-white/10 hover:text-white">Pricing</a>
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-white/90 transition hover:bg-white/10 hover:text-white">Login</a>
                <a href="{{ route('register') }}" class="rounded-full bg-white px-5 py-3 text-sm font-black text-[#513CC7] shadow-lg shadow-[#513CC7]/20 transition hover:-translate-y-0.5">Create Account</a>
            </div>
            </div>
        </nav>

        <section class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 sm:py-24 lg:grid-cols-[1fr_0.9fr]">
            <div>
                <p class="mb-5 inline-flex rounded-full bg-white px-4 py-2 text-sm font-black text-[#513CC7] shadow-sm ring-1 ring-[#513CC7]/10">
                    🎵 Ages 4–17 • Guitar & Keyboard lessons
                </p>
                <h1 class="max-w-4xl font-display text-5xl tracking-tight text-[#1C1F2F] sm:text-7xl">
                    Music lessons <span class="text-[#513CC7]">that help children shine</span>
                </h1>
                <p class="mt-6 max-w-2xl text-lg font-bold leading-8 text-[#1C1F2F]/70">
                    {{ $settings['heroSubtitle'] }}
                </p>
                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="rounded-full bg-[#513CC7] px-7 py-4 font-black text-white shadow-xl shadow-[#513CC7]/25 transition hover:-translate-y-0.5 hover:brightness-95">
                        {{ $settings['ctaText'] }}
                    </a>
                    <a href="{{ route('academy.index') }}" class="rounded-full bg-white px-7 py-4 font-black text-[#513CC7] shadow-sm ring-1 ring-[#513CC7]/10 transition hover:-translate-y-0.5">
                        Explore lessons
                    </a>
                </div>
            </div>

            <div class="relative">
                <div @class([
                    'relative overflow-hidden rounded-[1.2rem] shadow-2xl shadow-[#1C1F2F]/10',
                    'bg-[#333333] p-8 lg:p-12' => ! $introVideo,
                ])>
                    @if ($introVideo)
                        <div class="relative aspect-video" x-data="{ started: false }">
                            <video
                                x-ref="heroVideo"
                                class="h-full w-full object-cover"
                                preload="metadata"
                                playsinline
                                @play="started = true"
                                @if (\App\Support\Media::url($introVideo->poster_url, $introVideo->poster)) poster="{{ \App\Support\Media::url($introVideo->poster_url, $introVideo->poster) }}" @endif
                            >
                                <source src="{{ \App\Support\Media::url($introVideo->video_url, $introVideo->video) }}">
                                Your browser does not support the video element.
                            </video>

                            <button
                                x-show="! started"
                                type="button"
                                class="absolute inset-0 grid place-items-center bg-black/40 transition duration-300 hover:bg-black/45 focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-[-4px] focus-visible:outline-white"
                                aria-label="Play introduction video"
                                @click="started = true; $refs.heroVideo.controls = true; $refs.heroVideo.play()"
                            >
                                <span class="vvmi-hero-play-button grid h-20 w-20 place-items-center rounded-full bg-red-600 text-white shadow-2xl sm:h-24 sm:w-24">
                                    <svg class="ml-1 h-9 w-9 sm:h-11 sm:w-11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    @else
                    <div class="grid aspect-video place-items-center rounded-[1.2rem] bg-[#333333] text-white">
                        <span class="grid h-20 w-20 place-items-center rounded-full bg-white/85 text-3xl text-[#333333] shadow-xl">▶</span>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-12">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="font-black uppercase tracking-[0.2em] text-[#513CC7]">Featured instruments</p>
                    <h2 class="mt-2 text-4xl font-black text-[#1C1F2F]">Start with a sound children love</h2>
                </div>
            </div>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($featuredInstruments as $instrument)
                    <x-instrument-card :instrument="$instrument" />
                @empty
                    <x-loading-skeleton />
                    <x-loading-skeleton />
                @endforelse
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl gap-8 px-6 py-12 lg:grid-cols-3">
            <div class="rounded-[2rem] bg-white p-8 shadow-sm ring-1 ring-[#513CC7]/10 lg:col-span-2">
                <p class="font-black uppercase tracking-[0.2em] text-[#513CC7]">Beginner-friendly learning</p>
                <h2 class="mt-3 text-4xl font-black text-[#1C1F2F]">Small lessons. Clear steps. Big confidence.</h2>
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-[#513CC7]/5 p-5"><p class="text-3xl">👋</p><h3 class="mt-3 font-black">Meet the instrument</h3><p class="mt-2 text-sm text-[#1C1F2F]/70">Gentle introductions for young learners.</p></div>
                    <div class="rounded-3xl bg-[#513CC7]/5 p-5"><p class="text-3xl">🎯</p><h3 class="mt-3 font-black">Practice with purpose</h3><p class="mt-2 text-sm text-[#1C1F2F]/70">Ordered lessons keep every child moving.</p></div>
                    <div class="rounded-3xl bg-[#513CC7]/5 p-5"><p class="text-3xl">🏆</p><h3 class="mt-3 font-black">Celebrate progress</h3><p class="mt-2 text-sm text-[#1C1F2F]/70">Checkmarks and progress bars make wins visible.</p></div>
                </div>
            </div>
            <div class="rounded-[2rem] bg-[#513CC7] p-8 text-white shadow-xl shadow-[#513CC7]/20">
                <p class="text-5xl">🚀</p>
                <h3 class="mt-6 text-2xl font-black">Ready when they are</h3>
                <p class="mt-3 text-white/80">Students can pause, return later, and continue exactly where they stopped.</p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-12">
            <p class="font-black uppercase tracking-[0.2em] text-[#513CC7]">Parent notes</p>
            <h2 class="mt-2 text-4xl font-black text-[#1C1F2F]">Testimonials</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <x-testimonial-card quote="The lessons feel friendly, structured, and easy for my child to follow." name="Mrs. Ade" role="Parent" />
                <x-testimonial-card quote="I love seeing clear progress instead of wondering what was watched." name="Mr. Daniel" role="Parent" />
                <x-testimonial-card quote="The colorful design makes practice feel like a reward." name="Aunty Joy" role="Guardian" />
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-12">
            <p class="font-black uppercase tracking-[0.2em] text-[#513CC7]/75">Coming soon</p>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($comingSoonInstruments as $instrument)
                    <x-instrument-card :instrument="$instrument" />
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="rounded-[2.5rem] bg-[#513CC7] p-8 text-center text-white shadow-2xl shadow-[#513CC7]/20 sm:p-12">
                <h2 class="text-4xl font-black">Let the first lesson begin today.</h2>
                <p class="mx-auto mt-4 max-w-2xl text-white/90">Create a free student account and start learning Guitar or Keyboard with a joyful, guided curriculum.</p>
                <a href="{{ route('register') }}" class="mt-8 inline-flex rounded-full bg-white px-8 py-4 font-black text-[#513CC7] transition hover:-translate-y-0.5">Start free</a>
            </div>
        </section>

        <x-site-footer />
    </div>
</body>
</html>
