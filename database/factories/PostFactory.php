<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $title = fake()->sentence(),
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'is_featured' => fake()->boolean(),
            'is_published' => $isPublished = fake()->boolean(),
            'published_at' => $isPublished ? now() : null,
        ];
    }
}
