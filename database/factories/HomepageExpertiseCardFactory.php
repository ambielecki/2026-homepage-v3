<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HomepageExpertiseCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomepageExpertiseCard>
 */
class HomepageExpertiseCardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
        ];
    }
}
