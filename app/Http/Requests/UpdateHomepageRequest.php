<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'expertise_cards.*.id' => ['nullable', 'integer', 'exists:homepage_expertise_cards,id'],
            'expertise_cards.*.title' => ['nullable', 'string', 'max:255'],
            'expertise_cards.*.description' => ['nullable', 'string', 'max:2000'],
            'expertise_cards.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'expertise_cards.*.is_active' => ['sometimes', 'boolean'],
            'expertise_cards.*.remove' => ['sometimes', 'boolean'],
            'projects' => ['nullable', 'array'],
            'projects.*.id' => ['nullable', 'integer', 'exists:homepage_projects,id'],
            'projects.*.image_id' => ['nullable', 'integer', 'exists:images,id'],
            'projects.*.title' => ['nullable', 'string', 'max:255'],
            'projects.*.url' => ['nullable', 'url', 'max:255'],
            'projects.*.description' => ['nullable', 'string', 'max:2000'],
            'projects.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'projects.*.is_active' => ['sometimes', 'boolean'],
            'projects.*.remove' => ['sometimes', 'boolean'],
            'experiences' => ['nullable', 'array'],
            'experiences.*.id' => ['nullable', 'integer', 'exists:homepage_experiences,id'],
            'experiences.*.title' => ['nullable', 'string', 'max:255'],
            'experiences.*.description' => ['nullable', 'string', 'max:2000'],
            'experiences.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'experiences.*.is_active' => ['sometimes', 'boolean'],
            'experiences.*.remove' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->validateRepeatableRows($validator, 'expertise_cards');
            $this->validateRepeatableRows($validator, 'projects');
            $this->validateRepeatableRows($validator, 'experiences');
        });
    }

    private function validateRepeatableRows(Validator $validator, string $group): void
    {
        $rows = $this->input($group, []);

        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $index => $row) {
            if (! is_array($row) || $this->booleanValue($row['remove'] ?? false)) {
                continue;
            }

            $hasRowContent = filled($row['id'] ?? null)
                || filled($row['title'] ?? null)
                || filled($row['description'] ?? null)
                || filled($row['image_id'] ?? null);

            if (! $hasRowContent) {
                continue;
            }

            if (blank($row['title'] ?? null)) {
                $validator->errors()->add(sprintf('%s.%s.title', $group, $index), 'The title field is required.');
            }

            if (blank($row['description'] ?? null)) {
                $validator->errors()->add(sprintf('%s.%s.description', $group, $index), 'The description field is required.');
            }
        }
    }

    private function booleanValue(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
