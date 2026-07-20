<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Homepage;
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
            'homepage_id' => Homepage::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
