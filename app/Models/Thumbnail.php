<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Thumbnail extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'thumbnailable_id',
        'thumbnailable_type',
        'title',
        'path',
        'alt_text',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public static function validationRules(?self $thumbnail = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'is_primary' => ['boolean'],
        ];
    }

    public function thumbnailable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
