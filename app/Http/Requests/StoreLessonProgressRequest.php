<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'watched_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'last_watched_second' => ['nullable', 'integer', 'min:0'],
            'event_type' => ['nullable', 'string', 'in:play,pause,progress,completed,ended,visibility'],
        ];
    }
}
