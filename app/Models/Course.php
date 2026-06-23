<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'instrument_id',
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'order',
        'is_premium',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public static function validationRules(?self $course = null): array
    {
        return [
            'instrument_id' => ['required', 'uuid', 'exists:instruments,id'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'is_premium' => ['boolean'],
        ];
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('lesson_order');
    }

    public function thumbnails()
    {
        return $this->morphMany(Thumbnail::class, 'thumbnailable');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
