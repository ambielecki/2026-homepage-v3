<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveHomepageProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'image_id' => ['nullable', 'integer', 'exists:images,id'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }
}
