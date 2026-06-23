<x-app-layout>
    <div class="bg-[#F8F6F2] py-16">
        <div class="vvmi-container">
            <section class="mx-auto max-w-2xl rounded-[2.5rem] bg-white p-8 text-center shadow-[0_18px_60px_rgba(28,31,47,0.08)] sm:p-12">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-red-50 text-4xl text-red-600">!</div>
                <p class="vvmi-eyebrow mt-6">Payment incomplete</p>
                <h1 class="mt-3 text-4xl vvmi-heading">Your subscription was not activated</h1>
                <p class="mt-5 vvmi-body">{{ $message }}</p>
                <a href="{{ route('subscriptions.index') }}" class="vvmi-button-primary mt-8">Return to pricing</a>
            </section>
        </div>
    </div>
</x-app-layout>
