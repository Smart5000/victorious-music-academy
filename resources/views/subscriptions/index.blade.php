<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="vvmi-eyebrow">Premium Learning</p>
            <h2 class="text-3xl vvmi-heading">Choose your learning plan</h2>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="vvmi-container">
            @if (session('status'))
                <div class="mb-8 rounded-2xl bg-[#513CC7]/10 p-4 font-bold text-[#513CC7]">{{ session('status') }}</div>
            @endif

            @if ($activeSubscription)
                <section class="mb-10 rounded-[2rem] bg-[#513CC7] p-6 text-white shadow-xl shadow-[#513CC7]/20 sm:p-8">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-white/75">Active subscription</p>
                    <h3 class="mt-2 text-3xl font-display">{{ $activeSubscription->plan->name }}</h3>
                    <p class="mt-3 font-semibold text-white/85">
                        Your premium learning access is active
                        @if ($activeSubscription->ends_at)
                            until {{ $activeSubscription->ends_at->format('M j, Y') }}.
                        @else
                            and ready to use.
                        @endif
                    </p>
                </section>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($plans as $plan)
                    <article class="vvmi-card-flat flex flex-col p-7">
                        <p class="vvmi-eyebrow">{{ str($plan->billing_interval)->headline() }}</p>
                        <h3 class="mt-3 text-3xl vvmi-heading">{{ $plan->name }}</h3>
                        <p class="mt-4 text-4xl font-black text-[#513CC7]">{{ $plan->price_label }}</p>
                        <p class="mt-1 text-sm font-bold text-[#1C1F2F]/60">per {{ $plan->interval_label }}</p>
                        @if ($plan->description)
                            <p class="mt-5 flex-1 vvmi-body">{{ $plan->description }}</p>
                        @else
                            <p class="mt-5 flex-1 vvmi-body">Unlock premium courses and lessons for young musicians.</p>
                        @endif

                        @auth
                            <form method="POST" action="{{ route('subscriptions.subscribe', $plan) }}" class="mt-7">
                                @csrf
                                <button class="vvmi-button-primary w-full" @disabled($activeSubscription)>
                                    {{ $activeSubscription ? 'Currently subscribed' : 'Subscribe' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="vvmi-button-primary mt-7 w-full">Login to subscribe</a>
                        @endauth
                    </article>
                @empty
                    <x-empty-state
                        class="md:col-span-3"
                        icon="🎵"
                        title="Subscription plans are coming soon"
                        message="Please check back shortly."
                    />
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
