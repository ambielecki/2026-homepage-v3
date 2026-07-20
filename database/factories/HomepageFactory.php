<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Homepage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Homepage>
 */
class HomepageFactory extends Factory
{
    public function definition(): array
    {
        return Homepage::defaultAttributes(fake()->words(3, true));
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }
}
