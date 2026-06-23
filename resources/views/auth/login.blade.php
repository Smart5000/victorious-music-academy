<x-guest-layout>
    <div>
        <p class="vvmi-eyebrow">Welcome back</p>
        <h1 class="mt-2 text-3xl vvmi-heading">Continue your music journey</h1>
        <p class="mt-2 vvmi-body">Log in to resume lessons, track progress, and keep practicing.</p>
    </div>

    <x-auth-session-status class="mb-4 mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm font-bold text-[#1C1F2F]/75">
                <input id="remember_me" type="checkbox" class="rounded border-[#513CC7]/20 text-[#513CC7] shadow-sm focus:ring-[#513CC7]" name="remember">
                {{ __('Remember me') }}
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-black text-[#513CC7] transition hover:text-[#513CC7]/80" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button class="vvmi-button-primary w-full text-base">
            {{ __('Log in') }}
        </button>

        <p class="text-center text-sm text-[#1C1F2F]/75">
            New to the academy?
            <a href="{{ route('register') }}" class="font-black text-[#513CC7] hover:text-[#513CC7]/80">Create a student account</a>
        </p>
    </form>
</x-guest-layout>
