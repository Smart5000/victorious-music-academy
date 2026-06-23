@php
    $whatsappNumber = preg_replace('/\D+/', '', config('services.whatsapp.number', '2348053894724'));
    $whatsappMessage = "Hello Victorious Music Academy, I am interested in buying {$product->title} for {$product->price_label}. Please send me more details.";
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="vvmi-eyebrow">{{ $product->category->name }}</p>
                <h2 class="text-3xl vvmi-heading">{{ $product->title }}</h2>
            </div>
            <a href="{{ route('store.index') }}" class="vvmi-button-ghost self-start sm:self-auto">Back to Store</a>
        </div>
    </x-slot>

    <div
        class="bg-[#F8F6F2] py-12"
        x-data="storeOrder()"
        data-whatsapp-number="{{ $whatsappNumber }}"
        data-whatsapp-message="{{ $whatsappMessage }}"
        @keydown.escape.window="closeOrderForm"
    >
        <div class="vvmi-container space-y-12">
            <section class="grid gap-8 rounded-[2.5rem] bg-white p-6 shadow-[0_18px_60px_rgba(28,31,47,0.08)] ring-1 ring-[#513CC7]/10 lg:grid-cols-[0.9fr_1.1fr] lg:p-10">
                <div class="overflow-hidden rounded-[2rem] bg-[#513CC7]/10">
                    @if ($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->title }}" class="aspect-[4/3] w-full object-cover">
                    @else
                        <div class="grid aspect-[4/3] place-items-center text-center text-[#513CC7]">
                            <div>
                                <p class="text-7xl">🎼</p>
                                <p class="mt-4 font-black">{{ $product->product_type_label }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="vvmi-eyebrow">{{ $product->category->name }}</p>
                    <h1 class="mt-3 font-display text-4xl leading-tight text-[#1C1F2F] sm:text-5xl">{{ $product->title }}</h1>
                    <p class="mt-5 text-2xl font-black text-[#513CC7]">{{ $product->price_label }}</p>
                    @if ($product->description)
                        <p class="mt-5 text-lg leading-8 text-[#1C1F2F]/75">{{ $product->description }}</p>
                    @endif

                    <div class="mt-7 flex flex-wrap gap-3">
                        <span class="rounded-full bg-[#513CC7]/10 px-4 py-2 text-sm font-black text-[#513CC7]">{{ $product->product_type_label }}</span>
                        @if ($product->is_new_release)
                            <span class="rounded-full bg-[#513CC7]/10 px-4 py-2 text-sm font-black text-[#513CC7]">New Release</span>
                        @endif
                        @if ($product->is_free)
                            <span class="rounded-full bg-[#513CC7]/10 px-4 py-2 text-sm font-black text-[#513CC7]">Free Material</span>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @if ($product->is_free)
                            @if ($product->isMaterials() && $product->material_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->material_file))
                                <a href="{{ asset('storage/'.$product->material_file) }}" class="vvmi-button-primary" download>
                                    Free Download
                                </a>
                            @endif
                        @elseif ($product->isInstrument())
                            <button type="button" class="vvmi-button-primary" @click="contactToBuy">
                                Contact to Buy
                            </button>
                        @else
                            <a href="mailto:{{ config('mail.from.address') }}?subject=Product enquiry: {{ rawurlencode($product->title) }}" class="vvmi-button-primary">
                                Contact to Buy
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            @if ($relatedProducts->isNotEmpty())
                <section>
                    <div class="mb-6">
                        <p class="vvmi-eyebrow">Related</p>
                        <h2 class="mt-2 text-3xl vvmi-heading">More from {{ $product->category->name }}</h2>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <x-product-card :product="$relatedProduct" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div
            x-cloak
            x-show="orderFormOpen"
            x-transition.opacity
            class="fixed inset-0 z-[70] grid place-items-center bg-[#1C1F2F]/70 p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="store-order-title"
            @click.self="closeOrderForm"
        >
            <div x-transition class="w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between gap-5">
                    <div>
                        <p class="vvmi-eyebrow">Order enquiry</p>
                        <h2 id="store-order-title" class="mt-2 text-3xl vvmi-heading">Contact to Buy</h2>
                    </div>
                    <button
                        type="button"
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[#513CC7]/10 text-xl font-black text-[#513CC7] transition hover:bg-[#513CC7] hover:text-white focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-2 focus-visible:outline-[#513CC7]"
                        aria-label="Close order form"
                        @click="closeOrderForm"
                    >
                        &times;
                    </button>
                </div>

                <div x-show="!orderSubmitted" class="mt-6">
                    <form @submit.prevent="placeOrder" novalidate>
                        <div>
                            <label for="order-name" class="text-sm font-black text-[#1C1F2F]">Name</label>
                            <input
                                id="order-name"
                                x-ref="orderName"
                                x-model.trim="name"
                                type="text"
                                autocomplete="name"
                                required
                                class="mt-2 w-full rounded-2xl border border-[#513CC7]/20 px-4 py-3 text-[#1C1F2F] focus:border-[#513CC7] focus:ring-[#513CC7]"
                            >
                            <p x-show="showErrors && !name" class="mt-2 text-sm font-bold text-red-600">Please enter your name.</p>
                        </div>

                        <div class="mt-5">
                            <label for="order-phone" class="text-sm font-black text-[#1C1F2F]">Phone number</label>
                            <input
                                id="order-phone"
                                x-model.trim="phone"
                                type="tel"
                                autocomplete="tel"
                                required
                                class="mt-2 w-full rounded-2xl border border-[#513CC7]/20 px-4 py-3 text-[#1C1F2F] focus:border-[#513CC7] focus:ring-[#513CC7]"
                            >
                            <p x-show="showErrors && !phone" class="mt-2 text-sm font-bold text-red-600">Please enter your phone number.</p>
                        </div>

                        <button type="submit" class="vvmi-button-primary mt-6 w-full justify-center">Place Order</button>
                        <p class="mt-3 text-center text-sm font-semibold text-[#1C1F2F]/70">We’ll contact you shortly to complete your order.</p>
                    </form>
                </div>

                <div x-show="orderSubmitted" class="mt-6 rounded-2xl bg-[#513CC7]/10 p-5 text-center">
                    <p class="font-black text-[#513CC7]">Thank you. We’ll contact you shortly about your order.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@once
    <script>
        window.storeOrder = () => ({
            orderFormOpen: false,
            orderSubmitted: false,
            showErrors: false,
            name: '',
            phone: '',
            whatsappNumber: '',
            whatsappMessage: '',

            init() {
                this.whatsappNumber = this.$el.dataset.whatsappNumber;
                this.whatsappMessage = this.$el.dataset.whatsappMessage;
            },

            contactToBuy() {
                const encodedMessage = encodeURIComponent(this.whatsappMessage);
                const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

                if (isMobile) {
                    window.location.href = `https://wa.me/${this.whatsappNumber}?text=${encodedMessage}`;

                    return;
                }

                let whatsappOpened = false;
                const detectWhatsApp = () => {
                    if (document.hidden) {
                        whatsappOpened = true;
                    }
                };

                document.addEventListener('visibilitychange', detectWhatsApp);
                window.location.href = `whatsapp://send?phone=${this.whatsappNumber}&text=${encodedMessage}`;

                window.setTimeout(() => {
                    document.removeEventListener('visibilitychange', detectWhatsApp);

                    if (! whatsappOpened) {
                        this.openOrderForm();
                    }
                }, 1500);
            },

            openOrderForm() {
                this.orderFormOpen = true;
                this.orderSubmitted = false;
                this.showErrors = false;
                this.$nextTick(() => this.$refs.orderName?.focus());
            },

            closeOrderForm() {
                this.orderFormOpen = false;
            },

            placeOrder() {
                this.showErrors = true;

                if (! this.name || ! this.phone) {
                    return;
                }

                this.orderSubmitted = true;
                this.showErrors = false;
            },
        });
    </script>
@endonce
