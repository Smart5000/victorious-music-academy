<x-app-layout>
    <div class="bg-[#F8F6F2] py-16">
        <div class="vvmi-container">
            <section class="mx-auto max-w-2xl rounded-[2.5rem] bg-white p-8 text-center shadow-[0_18px_60px_rgba(28,31,47,0.08)] sm:p-12">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-[#513CC7]/10 text-4xl">✓</div>
                <p class="vvmi-eyebrow mt-6">Payment successful</p>
                <h1 class="mt-3 text-4xl vvmi-heading">Premium learning is unlocked</h1>
                <p class="mt-5 vvmi-body">Your {{ $subscription->plan->name }} subscription is now active.</p>
                <a href="{{ route('academy.index') }}" class="vvmi-button-primary mt-8">Start learning</a>
            </section>
        </div>
    </div>
</x-app-layout>
