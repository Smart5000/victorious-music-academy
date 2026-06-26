<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StoreBanner;
use App\Support\Media;
use Illuminate\Contracts\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $storeBanner = StoreBanner::query()
            ->active()
            ->latest('updated_at')
            ->first();

        if ($storeBanner && ! Media::exists($storeBanner->banner_url, $storeBanner->image)) {
            $storeBanner = null;
        }

        return view('store.index', [
            'storeBanner' => $storeBanner,
            'freeProducts' => Product::query()
                ->published()
                ->free()
                ->with('category')
                ->ordered()
                ->limit(4)
                ->get(),
            'newReleaseProducts' => Product::query()
                ->published()
                ->newRelease()
                ->with('category')
                ->ordered()
                ->limit(4)
                ->get(),
            'categories' => ProductCategory::query()
                ->active()
                ->withCount(['products' => fn ($products) => $products->published()])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_if(! $product->is_active || ! $product->category?->is_active, 404);

        $product->load('category');

        return view('store.show', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->published()
                ->where('product_category_id', $product->product_category_id)
                ->whereKeyNot($product->id)
                ->with('category')
                ->ordered()
                ->limit(4)
                ->get(),
        ]);
    }

    public function category(ProductCategory $category): View
    {
        abort_if(! $category->is_active, 404);

        $productsQuery = match ($category->slug) {
            'free-materials' => Product::query()->free(),
            'new-release' => Product::query()->newRelease(),
            default => $category->products(),
        };

        return view('store.category', [
            'category' => $category,
            'products' => $productsQuery
                ->published()
                ->with('category')
                ->ordered()
                ->paginate(12),
        ]);
    }
}
