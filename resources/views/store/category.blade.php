<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="vvmi-eyebrow">Store Category</p>
                <h2 class="text-3xl vvmi-heading">{{ $category->name }}</h2>
            </div>
            <a href="{{ route('store.index') }}" class="vvmi-button-ghost self-start sm:self-auto">Back to Store</a>
        </div>
    </x-slot>

    <div class="bg-[#F8F6F2] py-12">
        <div class="vvmi-container">
            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <x-empty-state
                        class="sm:col-span-2 lg:col-span-4"
                        icon="🛍️"
                        title="No products yet"
                        message="Products added to this category will appear here."
                    />
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
