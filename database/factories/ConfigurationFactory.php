<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Configuration;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ConfigurationFactory extends Factory
{
    protected $model = Configuration::class;

    public function definition(): array
    {
        return [

            'topic_weight' => Topic::factory(),
            'topic_calorie_in' => Topic::factory(),
            'topic_calorie_out' => Topic::factory(),
        ];
    }
}
