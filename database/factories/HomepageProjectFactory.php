<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HomepageProject;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomepageProject>
 */
class HomepageProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'image_id' => null,
            'title' => fake()->words(3, true),
            'url' => null,
            'description' => fake()->sentence(),
        ];
    }

    public function withImage(): self
    {
        return $this->state(fn (array $attributes): array => [
            'image_id' => Image::factory(),
        ]);
    }
}
