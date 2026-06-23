<div class="grid gap-6 md:grid-cols-3">
    @forelse ($plans as $plan)
        <article class="vvmi-card-flat flex flex-col p-7">
            <p class="vvmi-eyebrow">{{ str($plan->billing_interval)->headline() }}</p>
            <h3 class="mt-3 text-3xl vvmi-heading">{{ $plan->name }}</h3>
            <p class="mt-4 text-4xl font-black text-[#513CC7]">{{ $plan->price_label }}</p>
            <p class="mt-1 text-sm font-bold text-[#1C1F2F]/60">per {{ $plan->interval_label }}</p>
            <p class="mt-5 flex-1 vvmi-body">
                {{ $plan->description ?: 'Unlock premium courses and lessons for young musicians.' }}
            </p>

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
