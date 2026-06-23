<footer class="bg-[#513CC7] text-white">
    <div class="vvmi-container grid gap-10 py-12 md:grid-cols-[1.5fr_0.7fr_0.7fr]">
        <div>
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center gap-3 text-lg font-black">
                    <span class="grid h-20 w-20 place-items-center rounded-full bg-white shadow-sm ring-1 ring-white/40">
                        <span class="text-center font-display text-[11px] leading-none text-[#1C1F2F]">
                            <span class="block text-[#513CC7]">Victorious</span>
                            <span class="block">Music</span>
                        </span>
                    </span>
                    <span class="sr-only">Victorious Music Academy</span>
                </a>
                <p class="max-w-xl text-lg italic leading-8 text-white/90">
                    ...giving children everywhere the opportunity to enjoy a complete musical education
                </p>
            </div>
            @guest
                <a href="{{ route('register') }}" class="mt-7 inline-flex rounded-full bg-white px-7 py-3 text-sm font-black text-[#513CC7] shadow-lg shadow-[#513CC7]/20 transition hover:-translate-y-0.5">
                    Create A Free Account
                </a>
            @endguest
        </div>

        <div>
            <h3 class="font-black text-white">Learn Music</h3>
            <div class="mt-4 space-y-3 text-base font-bold text-white/85">
                <a class="block transition hover:text-white" href="{{ route('academy.index') }}">Lessons</a>
                <a class="block transition hover:text-white" href="{{ route('store.index') }}">Store</a>
                @auth
                    <a class="block transition hover:text-white" href="{{ route('academy.continue') }}">Continue Learning</a>
                    <a class="block transition hover:text-white" href="{{ route('dashboard') }}">Student Dashboard</a>
                @else
                    <a class="block transition hover:text-white" href="{{ route('login') }}">Login</a>
                    <a class="block transition hover:text-white" href="{{ route('register') }}">Create Account</a>
                @endauth
            </div>
        </div>

        <div>
            <h3 class="font-black text-white">Get Help</h3>
            <div class="mt-4 space-y-3 text-base font-bold text-white/85">
                @auth
                    <a class="block transition hover:text-white" href="{{ route('profile.edit') }}">Profile</a>
                    <a class="block transition hover:text-white" href="{{ route('academy.continue') }}">Support</a>
                @else
                    <a class="block transition hover:text-white" href="{{ route('login') }}">Contact Us</a>
                    <a class="block transition hover:text-white" href="{{ route('register') }}">Support</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="bg-[#F8F6F2] py-5 text-center text-sm font-bold text-[#1C1F2F]/75">
        © {{ date('Y') }} <span class="text-[#513CC7]">Victorious Music Academy</span> || All Rights Reserved
    </div>
</footer>
