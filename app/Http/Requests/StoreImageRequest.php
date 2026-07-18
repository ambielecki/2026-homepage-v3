<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
            'image' => ['required', 'image', 'max:10240'],
            'description' => ['nullable', 'string', 'max:2000'],
            'alt_text' => ['required', 'string', 'max:255'],
            'is_header' => ['sometimes', 'boolean'],
        ];
    }
}
