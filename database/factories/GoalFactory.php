<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
final class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $topic = Topic::factory()->create();
        $user = $topic->user;

        return [
            'name' => fake()->unique()->word(),
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'type' => fake()->randomElement(GoalTypeEnum::cases()),
            'target' => fake()->randomFloat(2, 0, 100),
            'ended_at' => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
