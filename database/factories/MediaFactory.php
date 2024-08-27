<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 day' );

        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['image', 'video']),
            'url' => $this->faker->imageUrl(),
            'mime_type' => $this->faker->mimeType(),
            'size' => $this->faker->numberBetween(100, 5000000),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
