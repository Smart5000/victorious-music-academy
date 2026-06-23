<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-[#513CC7] text-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Victorious Music Academy homepage">
                    <span class="grid h-14 w-14 place-items-center rounded-full bg-white text-center shadow-lg">
                        <span class="font-display text-[10px] leading-none text-[#1C1F2F]">Victorious<br><span class="text-[#513CC7]">Music</span></span>
                    </span>
                    <span class="sr-only">Victorious Music Academy</span>
                </a>

                <div class="hidden items-center gap-4 md:flex">
                    @guest
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-nav-link>
                    @endguest
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                        <x-nav-link :href="route('academy.index')" :active="request()->routeIs('academy.*') || request()->routeIs('courses.*') || request()->routeIs('lessons.*')">{{ __('Lessons') }}</x-nav-link>
                    @endauth
                    <x-nav-link :href="route('store.index')" :active="request()->routeIs('store.*')">{{ __('Store') }}</x-nav-link>
                </div>
            </div>

            <div class="hidden items-center gap-5 md:flex">
                @auth
                    <a href="{{ route('academy.continue') }}" class="font-bold text-white transition hover:text-white/80">Continue</a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 rounded-full bg-white/10 px-4 py-2 text-sm font-bold text-white transition hover:bg-white/15 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-white transition hover:text-white/80">Login</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-white px-5 py-3 text-sm font-black text-[#513CC7] shadow-lg shadow-[#513CC7]/20 transition hover:-translate-y-0.5">Create Account</a>
                @endauth
            </div>

            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl bg-white/10 p-3 text-white transition hover:bg-white/15 md:hidden" aria-label="Toggle navigation">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-white/10 bg-[#513CC7] md:hidden">
        <div class="space-y-1 px-4 py-4">
            @guest
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-responsive-nav-link>
            @endguest
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('academy.index')" :active="request()->routeIs('academy.*') || request()->routeIs('courses.*') || request()->routeIs('lessons.*')">{{ __('Lessons') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('academy.continue')" :active="request()->routeIs('academy.continue')">{{ __('Continue Learning') }}</x-responsive-nav-link>
            @endauth
            <x-responsive-nav-link :href="route('store.index')" :active="request()->routeIs('store.*')">{{ __('Store') }}</x-responsive-nav-link>
            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">{{ __('Login') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">{{ __('Create Account') }}</x-responsive-nav-link>
            @endguest
        </div>
    </div>
</nav>
