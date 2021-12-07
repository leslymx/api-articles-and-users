<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'SKU' => Str::random(8),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph(),
            'user_id' => random_int(1, 10),
            'cover' => $this->faker->imageUrl(),
            'like' => random_int(1, 5)
        ];
    }
}
