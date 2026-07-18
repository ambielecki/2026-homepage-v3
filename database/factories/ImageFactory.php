<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    public function definition(): array
    {
        $name = (string) Str::uuid7();

        return [
            'name' => $name,
            'description' => fake()->sentence(),
            'alt_text' => fake()->words(4, true),
            'has_sizes' => false,
            'is_header' => false,
            'original_path' => sprintf('images/%s/%s.jpg', $name, $name),
            'original_extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'width' => 1200,
            'height' => 800,
        ];
    }
}
