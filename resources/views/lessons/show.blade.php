<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">
                    {{ $lesson->course->instrument->title }} • {{ $lesson->course->category->name }}
                </p>
                <h2 class="text-3xl font-black leading-tight text-[#1C1F2F]">{{ $lesson->title }}</h2>
            </div>
            <a href="{{ route('courses.show', $lesson->course) }}" class="inline-flex rounded-full bg-white px-5 py-3 text-sm font-black text-[#513CC7] shadow-sm ring-1 ring-[#513CC7]/10">
                Back to course
            </a>
        </div>
    </x-slot>

    @php
        $currentPercent = $progress?->watched_percentage ?? 0;
        $lastPosition = $progress?->last_watched_second ?? 0;
        $youtubeVideoId = $lesson->youtubeVideoId();
    @endphp

    <div
        x-data="lessonPlayer({
            endpoint: @js(route('lessons.progress.store', $lesson)),
            token: @js(csrf_token()),
            lastPosition: @js($lastPosition),
            startingPercent: @js($currentPercent),
            nextLessonUrl: @js($nextLesson ? route('lessons.show', $nextLesson) : null),
            playerType: @js($youtubeVideoId ? 'youtube' : 'html5'),
            youtubeVideoId: @js($youtubeVideoId),
        })"
        class="bg-[#F8F6F2] py-8 sm:py-12"
    >
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_380px] lg:px-8">
            <main class="overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-[#513CC7]/10">
                <div class="relative bg-slate-950">
                    @if ($youtubeVideoId)
                        <div class="aspect-video w-full bg-black">
                            <div id="youtube-player" class="h-full w-full"></div>
                        </div>
                    @elseif ($lesson->video_url)
                        <video
                            x-ref="video"
                            class="aspect-video w-full bg-black"
                            controls
                            preload="metadata"
                            playsinline
                            @loadedmetadata="resumeVideo"
                            @play="markEvent('play')"
                            @pause="markEvent('pause')"
                            @timeupdate="trackProgress"
                            @ended="completeLesson"
                        >
                            <source src="{{ $lesson->video_url }}">
                            Your browser does not support this video format.
                        </video>
                    @else
                        <div class="flex aspect-video items-center justify-center p-8 text-center text-white">
                            <div>
                                <p class="text-6xl">🎵</p>
                                <p class="mt-5 text-xl font-black">Upload a lesson video in the admin CMS.</p>
                                <p class="mt-2 text-sm text-white/75">The player is ready and will track progress once a video URL is added.</p>
                            </div>
                        </div>
                    @endif

                    <div x-show="saving" x-cloak class="absolute right-4 top-4 rounded-full bg-white/95 px-4 py-2 text-sm font-black text-[#513CC7] shadow-lg">
                        Saving progress...
                    </div>
                    <div x-show="saved" x-cloak class="absolute right-4 top-4 rounded-full bg-[#513CC7] px-4 py-2 text-sm font-black text-white shadow-lg">
                        Saved ✅
                    </div>
                </div>

                <div class="space-y-6 p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#513CC7]">Lesson {{ $lesson->lesson_order }}</p>
                            <h1 class="mt-2 text-3xl font-black text-[#1C1F2F]">{{ $lesson->title }}</h1>
                            <p class="mt-3 max-w-3xl leading-7 text-[#1C1F2F]/70">{{ $lesson->description }}</p>
                        </div>
                        <button
                            type="button"
                            @click="curriculumOpen = true"
                            class="inline-flex rounded-full bg-[#513CC7] px-5 py-3 text-sm font-black text-white lg:hidden"
                        >
                            Curriculum
                        </button>
                    </div>

                    <div class="rounded-[1.5rem] bg-[#513CC7]/5 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-black text-[#1C1F2F]">Your lesson progress</p>
                                <p class="text-sm text-[#1C1F2F]/60" x-text="statusText"></p>
                            </div>
                            <span class="rounded-full bg-white px-4 py-2 text-sm font-black text-[#513CC7] shadow-sm" x-text="`${percent}%`"></span>
                        </div>
                        <div class="mt-4 h-4 overflow-hidden rounded-full bg-white">
                            <div class="h-full rounded-full bg-[#513CC7] transition-all duration-500" :style="`width: ${percent}%`"></div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 rounded-[1.5rem] bg-[#513CC7]/5 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <label class="flex items-center gap-3 font-black text-[#1C1F2F]">
                            <input type="checkbox" x-model="autoplayNext" class="rounded border-[#513CC7]/30 text-[#513CC7] focus:ring-[#513CC7]">
                            Autoplay next lesson
                        </label>

                        @if ($nextLesson)
                            <a href="{{ route('lessons.show', $nextLesson) }}" class="inline-flex justify-center rounded-full bg-[#513CC7] px-5 py-3 text-sm font-black text-white shadow-lg shadow-[#513CC7]/20">
                                Next: {{ $nextLesson->title }}
                            </a>
                        @else
                            <span class="inline-flex justify-center rounded-full bg-white px-5 py-3 text-sm font-black text-[#513CC7]">
                                Course complete 🎉
                            </span>
                        @endif
                    </div>
                </div>
            </main>

            <aside class="hidden lg:block">
                <div class="sticky top-6 rounded-[2rem] bg-white p-5 shadow-sm ring-1 ring-[#513CC7]/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#513CC7]">Curriculum</p>
                            <h2 class="mt-1 text-xl font-black text-[#1C1F2F]">{{ $lesson->course->title }}</h2>
                        </div>
                        <span class="rounded-full bg-[#513CC7]/10 px-3 py-2 text-xs font-black text-[#513CC7]">{{ $lesson->course->lessons->count() }} lessons</span>
                    </div>

                    <div class="mt-5 max-h-[70vh] space-y-3 overflow-y-auto pr-1">
                        @foreach ($lesson->course->lessons as $courseLesson)
                            <x-lesson-row :lesson="$courseLesson" :active="$courseLesson->is($lesson)" />
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>

        <div x-show="curriculumOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-[#1C1F2F]/50" @click="curriculumOpen = false"></div>
            <div class="absolute bottom-0 left-0 right-0 max-h-[82vh] overflow-y-auto rounded-t-[2rem] bg-white p-5 shadow-2xl">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[#513CC7]">Curriculum</p>
                        <h2 class="mt-1 text-xl font-black text-[#1C1F2F]">{{ $lesson->course->title }}</h2>
                    </div>
                    <button type="button" @click="curriculumOpen = false" class="rounded-full bg-[#513CC7]/10 px-4 py-2 font-black text-[#513CC7]">
                        Close
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach ($lesson->course->lessons as $courseLesson)
                        <x-lesson-row :lesson="$courseLesson" :active="$courseLesson->is($lesson)" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function lessonPlayer(config) {
            return {
                endpoint: config.endpoint,
                token: config.token,
                lastPosition: Number(config.lastPosition || 0),
                percent: Number(config.startingPercent || 0),
                nextLessonUrl: config.nextLessonUrl,
                playerType: config.playerType,
                youtubeVideoId: config.youtubeVideoId,
                youtubePlayer: null,
                youtubeProgressTimer: null,
                autoplayNext: false,
                curriculumOpen: false,
                saving: false,
                saved: false,
                lastSavedAt: 0,
                statusText: 'Ready to learn',

                init() {
                    if (this.playerType === 'youtube' && this.youtubeVideoId) {
                        this.loadYouTubePlayer();
                    }

                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.saveProgress('visibility', true);
                        }
                    });

                    window.addEventListener('beforeunload', () => {
                        this.saveProgress('pause', true);
                    });
                },

                loadYouTubePlayer() {
                    window.vvmiYouTubeReadyCallbacks = window.vvmiYouTubeReadyCallbacks || [];
                    window.vvmiYouTubeReadyCallbacks.push(() => this.createYouTubePlayer());

                    if (window.YT?.Player) {
                        this.createYouTubePlayer();
                        return;
                    }

                    if (!document.getElementById('youtube-iframe-api')) {
                        const script = document.createElement('script');
                        script.id = 'youtube-iframe-api';
                        script.src = 'https://www.youtube.com/iframe_api';
                        document.head.appendChild(script);
                    }

                    window.onYouTubeIframeAPIReady = () => {
                        (window.vvmiYouTubeReadyCallbacks || []).forEach((callback) => callback());
                        window.vvmiYouTubeReadyCallbacks = [];
                    };
                },

                createYouTubePlayer() {
                    if (this.youtubePlayer || !window.YT?.Player) {
                        return;
                    }

                    this.youtubePlayer = new YT.Player('youtube-player', {
                        videoId: this.youtubeVideoId,
                        playerVars: {
                            rel: 0,
                            modestbranding: 1,
                            playsinline: 1,
                        },
                        events: {
                            onReady: () => this.resumeVideo(),
                            onStateChange: (event) => this.handleYouTubeState(event),
                        },
                    });
                },

                handleYouTubeState(event) {
                    if (event.data === YT.PlayerState.PLAYING) {
                        this.markEvent('play');
                        this.youtubeProgressTimer = setInterval(() => this.trackProgress(), 1000);
                    }

                    if (event.data === YT.PlayerState.PAUSED) {
                        clearInterval(this.youtubeProgressTimer);
                        this.markEvent('pause');
                    }

                    if (event.data === YT.PlayerState.ENDED) {
                        clearInterval(this.youtubeProgressTimer);
                        this.completeLesson();
                    }
                },

                resumeVideo() {
                    if (this.playerType === 'youtube') {
                        if (this.youtubePlayer && this.lastPosition > 0) {
                            this.youtubePlayer.seekTo(this.lastPosition, true);
                            this.statusText = `Resumed at ${this.formatTime(this.lastPosition)}`;
                        }
                        return;
                    }

                    const video = this.$refs.video;

                    if (video && this.lastPosition > 0 && this.lastPosition < video.duration) {
                        video.currentTime = this.lastPosition;
                        this.statusText = `Resumed at ${this.formatTime(this.lastPosition)}`;
                    }
                },

                getCurrentTime() {
                    if (this.playerType === 'youtube') {
                        return this.youtubePlayer?.getCurrentTime?.() || 0;
                    }

                    return this.$refs.video?.currentTime || 0;
                },

                getDuration() {
                    if (this.playerType === 'youtube') {
                        return this.youtubePlayer?.getDuration?.() || 0;
                    }

                    return this.$refs.video?.duration || 0;
                },

                trackProgress() {
                    const duration = this.getDuration();

                    if (!duration) {
                        return;
                    }

                    this.percent = Math.min(100, Math.round((this.getCurrentTime() / duration) * 100));
                    this.statusText = this.percent >= 100 ? 'Completed' : `Watching • ${this.formatTime(this.getCurrentTime())}`;

                    if (Date.now() - this.lastSavedAt >= 8000) {
                        this.saveProgress('progress');
                    }
                },

                markEvent(eventType) {
                    this.statusText = eventType === 'play' ? 'Playing' : 'Paused';
                    this.saveProgress(eventType, true);
                },

                completeLesson() {
                    this.percent = 100;
                    this.statusText = 'Completed 🎉';
                    this.saveProgress('completed', true).then(() => {
                        if (this.autoplayNext && this.nextLessonUrl) {
                            window.location.href = this.nextLessonUrl;
                        }
                    });
                },

                async saveProgress(eventType = 'progress', force = false) {
                    const duration = this.getDuration();

                    if (!duration) {
                        return;
                    }

                    if (!force && Date.now() - this.lastSavedAt < 8000) {
                        return;
                    }

                    this.lastSavedAt = Date.now();
                    this.saving = true;
                    this.saved = false;

                    try {
                        const response = await fetch(this.endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.token,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                watched_percentage: this.percent,
                                last_watched_second: Math.round(this.getCurrentTime()),
                                event_type: eventType,
                            }),
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.percent = data.watched_percentage;
                            this.saved = true;
                            setTimeout(() => this.saved = false, 1400);
                        }
                    } finally {
                        this.saving = false;
                    }
                },

                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = Math.floor(seconds % 60).toString().padStart(2, '0');

                    return `${minutes}:${remainingSeconds}`;
                },
            };
        }
    </script>
</x-app-layout>
