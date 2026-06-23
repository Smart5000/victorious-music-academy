<x-guest-layout>
    <div>
        <p class="vvmi-eyebrow">Start free</p>
        <h1 class="mt-2 text-3xl vvmi-heading">Create your student account</h1>
        <p class="mt-2 vvmi-body">Join the academy and begin learning Guitar or Keyboard today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 focus:border-[#513CC7] focus:ring-[#513CC7]" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button class="vvmi-button-primary w-full text-base">
            {{ __('Register') }}
        </button>

        <p class="text-center text-sm text-[#1C1F2F]/75">
            Already have an account?
            <a href="{{ route('login') }}" class="font-black text-[#513CC7] hover:text-[#513CC7]/80">Log in</a>
        </p>
    </form>
</x-guest-layout>
