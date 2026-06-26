<?php

namespace App\Models;

use App\Support\CloudinaryModelMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, HasUuids;

    public const PRICE_TYPE_FREE = 'free';

    public const PRICE_TYPE_PAID = 'paid';

    public const PRODUCT_TYPE_INSTRUMENT = 'instrument';

    public const PRODUCT_TYPE_MATERIALS = 'materials';

    protected $fillable = [
        'product_category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'thumbnail_url',
        'thumbnail_public_id',
        'material_file',
        'material_url',
        'material_public_id',
        'price',
        'price_type',
        'currency',
        'product_type',
        'is_free',
        'is_new_release',
        'is_featured',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_free' => 'boolean',
        'is_new_release' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product): void {
            $product->product_type = match ($product->product_type) {
                'physical' => self::PRODUCT_TYPE_INSTRUMENT,
                'digital' => self::PRODUCT_TYPE_MATERIALS,
                default => $product->product_type ?: self::PRODUCT_TYPE_MATERIALS,
            };

            $product->slug = $product->slug ?: Str::slug($product->title);
            $product->currency = 'NGN';
            $product->is_free = $product->price_type === self::PRICE_TYPE_FREE;
            $product->price = $product->is_free ? 0 : ($product->price ?: 0);
            $product->display_order = $product->display_order ?: 0;
            $product->short_description = null;
            $product->is_featured = $product->is_featured ?? false;

            if ($product->isInstrument()) {
                $product->material_file = null;
            }

            CloudinaryModelMedia::sync($product, 'thumbnail', 'thumbnail_url', 'thumbnail_public_id');
            CloudinaryModelMedia::sync($product, 'material_file', 'material_url', 'material_public_id', 'raw');

            if (! $product->product_category_id) {
                $category = ProductCategory::query()->firstOrCreate(
                    ['slug' => $product->product_type],
                    [
                        'name' => $product->product_type_label,
                        'description' => null,
                        'is_active' => true,
                    ],
                );

                $product->product_category_id = $category->id;
            }
        });
    }

    public static function validationRules(?self $product = null): array
    {
        return [
            'product_category_id' => ['nullable', 'uuid', 'exists:product_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'material_file' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'price_type' => ['required', 'in:free,paid'],
            'currency' => ['nullable', 'string', 'max:10'],
            'product_type' => ['required', 'in:instrument,materials'],
            'is_free' => ['boolean'],
            'is_new_release' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function getPriceLabelAttribute(): string
    {
        if ($this->is_free || $this->price_type === self::PRICE_TYPE_FREE) {
            return 'Free';
        }

        return '₦'.number_format($this->price);
    }

    public function getProductTypeLabelAttribute(): string
    {
        return match ($this->product_type) {
            self::PRODUCT_TYPE_INSTRUMENT => 'Instrument',
            default => 'Materials',
        };
    }

    public function isInstrument(): bool
    {
        return $this->product_type === self::PRODUCT_TYPE_INSTRUMENT;
    }

    public function isMaterials(): bool
    {
        return $this->product_type === self::PRODUCT_TYPE_MATERIALS;
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)->whereHas('category', fn ($category) => $category->active());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->latest();
    }

    public function scopeFree($query)
    {
        return $query->where(function ($products) {
            $products->where('is_free', true)->orWhere('price_type', self::PRICE_TYPE_FREE);
        });
    }

    public function scopeNewRelease($query)
    {
        return $query->where('is_new_release', true);
    }
}
