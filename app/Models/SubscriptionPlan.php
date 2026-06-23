<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory, HasUuids;

    public const INTERVAL_MONTHLY = 'monthly';

    public const INTERVAL_QUARTERLY = 'quarterly';

    public const INTERVAL_ANNUALLY = 'annually';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_interval',
        'paystack_plan_code',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('price');
    }

    public function getPriceLabelAttribute(): string
    {
        return '₦'.number_format($this->price);
    }

    public function getIntervalLabelAttribute(): string
    {
        return match ($this->billing_interval) {
            self::INTERVAL_MONTHLY => 'month',
            self::INTERVAL_QUARTERLY => '3 months',
            self::INTERVAL_ANNUALLY => 'year',
            default => $this->billing_interval,
        };
    }

    public function amountInKobo(): int
    {
        return $this->price * 100;
    }
}
