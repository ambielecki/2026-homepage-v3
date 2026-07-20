<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomepageRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'hero_image_id' => ['nullable', 'integer', 'exists:images,id'],
            'hero_headline' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string', 'max:2000'],
            'expertise_headline' => ['required', 'string', 'max:255'],
            'expertise_title' => ['required', 'string', 'max:255'],
            'projects_headline' => ['required', 'string', 'max:255'],
            'projects_title' => ['required', 'string', 'max:255'],
            'projects_description' => ['required', 'string', 'max:2000'],
            'experience_headline' => ['required', 'string', 'max:255'],
            'experience_title' => ['required', 'string', 'max:255'],
            'experience_description' => ['required', 'string', 'max:2000'],
            'contact_headline' => ['required', 'string', 'max:255'],
            'contact_title' => ['required', 'string', 'max:255'],
            'contact_description' => ['nullable', 'string', 'max:2000'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'expertise_cards' => ['nullable', 'array'],
            'expertise_cards.*.id' => ['required', 'integer', 'exists:homepage_expertise_cards,id'],
            'expertise_cards.*.sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'expertise_cards.*.is_active' => ['sometimes', 'boolean'],
            'projects' => ['nullable', 'array'],
            'projects.*.id' => ['required', 'integer', 'exists:homepage_projects,id'],
            'projects.*.sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'projects.*.is_active' => ['sometimes', 'boolean'],
            'experiences' => ['nullable', 'array'],
            'experiences.*.id' => ['required', 'integer', 'exists:homepage_experiences,id'],
            'experiences.*.sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'experiences.*.is_active' => ['sometimes', 'boolean'],
        ];
    }
}
