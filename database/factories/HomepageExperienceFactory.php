<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HomepageExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomepageExperience>
 */
class HomepageExperienceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
        ];
    }
}
