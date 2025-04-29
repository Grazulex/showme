<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UnitEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
final class TopicFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(UnitEnum::cases()),
            'user_id' => User::factory(),
        ];
    }
}
