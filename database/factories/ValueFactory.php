<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Value>
 */
final class ValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topic = Topic::factory()->create();
        $user_id = $topic->user_id;

        return [
            'user_id' => $user_id,
            'topic_id' => $topic->id,
            'value' => fake()->randomFloat(2, 0, 100),
            'created_at' => now(),
            'diff_with_last' => fake()->randomFloat(2, -10, 10),
            'color' => fake()->randomElement(['red', 'green', 'blue']),
        ];
    }
}
