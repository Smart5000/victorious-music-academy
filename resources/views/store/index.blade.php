<x-app-layout>
    <x-slot name="header">
        <!-- <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-3xl vvmi-heading">Store</h2>
            </div>
        </div> -->
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="vvmi-container space-y-12">
            @if ($storeBanner)
                <section class="overflow-hidden rounded-[2rem] shadow-[0_18px_60px_rgba(28,31,47,0.08)]">
                    <img
                        src="{{ \App\Support\Media::url($storeBanner->banner_url, $storeBanner->image) }}"
                        alt="Store promotion"
                        class="h-44 w-full object-cover sm:h-64 lg:h-80"
                    >
                </section>
            @endif

            <section id="free-products">
                <div class="mb-26 flex items-end justify-between gap-4">
                    <h2 class="text-3xl vvmi-heading">Free</h2>
                    <a href="{{ route('store.categories.show', 'free-materials') }}" class="vvmi-button-ghost hidden sm:inline-flex">More</a>
                </div>

                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($freeProducts as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <x-empty-state
                            class="sm:col-span-2 lg:col-span-4"
                            icon="🎁"
                            title="Free products are coming soon"
                            message=" "
                        />
                    @endforelse
                </div>

                <a href="{{ route('store.categories.show', 'free-materials') }}" class="vvmi-button-ghost mt-6 w-full sm:hidden">More</a>
            </section>

            <section>
                <div class="mb-6 flex items-end justify-between gap-4">
                    <h2 class="text-3xl vvmi-heading">New Release</h2>
                    <a href="{{ route('store.categories.show', 'new-release') }}" class="vvmi-button-ghost hidden sm:inline-flex">More</a>
                </div>

                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($newReleaseProducts as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <x-empty-state
                            class="sm:col-span-2 lg:col-span-4"
                            icon="✨"
                            title="New releases are coming soon"
                            message=" "
                        />
                    @endforelse
                </div>

                <a href="{{ route('store.categories.show', 'new-release') }}" class="vvmi-button-ghost mt-6 w-full sm:hidden">More</a>
            </section>
        </div>
    </div>
</x-app-layout>
