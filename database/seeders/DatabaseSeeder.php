<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ActivityEnum;
use App\Enums\GoalTypeEnum;
use App\Enums\UnitEnum;
use App\Models\Configuration;
use App\Models\Goal;
use App\Models\Meal;
use App\Models\Topic;
use App\Models\User;
use App\Models\Value;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur de test avec des données réalistes
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@showme.test',
            'password' => bcrypt('password'),
            'height' => 175, // 175 cm
            'birth_at' => now()->subYears(35),
            'gender' => 'male',
            'activity' => ActivityEnum::MODERATELY_ACTIVE,
            'calories_each_day' => 2400, // TDEE calculé
        ]);

        // Créer les topics principaux
        $weightTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Weight',
            'slug' => 'weight',
            'description' => 'Body weight tracking',
            'unit' => UnitEnum::kilogram,
            'is_weight' => true,
        ]);

        $waistTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Waist Size',
            'slug' => 'waist-size',
            'description' => 'Waist circumference',
            'unit' => UnitEnum::centimeter,
            'is_weight' => false,
        ]);

        $distanceTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Distance',
            'slug' => 'distance',
            'description' => 'Distance walked/run',
            'unit' => UnitEnum::kilometer,
            'is_weight' => false,
        ]);

        $caloriesInTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Calories In',
            'slug' => 'calories-in',
            'description' => 'Daily calorie intake',
            'unit' => UnitEnum::calories,
            'is_weight' => false,
        ]);

        $caloriesOutTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Calories Out',
            'slug' => 'calories-out',
            'description' => 'Daily calories burned',
            'unit' => UnitEnum::calories,
            'is_weight' => false,
        ]);

        $bodyFatTopic = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Body Fat',
            'slug' => 'body-fat',
            'description' => 'Body fat percentage',
            'unit' => UnitEnum::percentage,
            'is_weight' => false,
        ]);

        // Créer des configurations système
        Configuration::factory()->create([
            'user_id' => $user->id,
            'key' => 'weight_topic_id',
            'value' => (string) $weightTopic->id,
        ]);

        Configuration::factory()->create([
            'user_id' => $user->id,
            'key' => 'calories_in_topic_id',
            'value' => (string) $caloriesInTopic->id,
        ]);

        Configuration::factory()->create([
            'user_id' => $user->id,
            'key' => 'calories_out_topic_id',
            'value' => (string) $caloriesOutTopic->id,
        ]);

        // Créer des valeurs pour le poids (30 derniers jours, tendance à la baisse)
        $startWeight = 85.0;
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weight = $startWeight - ($i * 0.15) + fake()->randomFloat(1, -0.3, 0.3); // Perte progressive

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $weightTopic->id,
                'value' => round($weight, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des valeurs pour le tour de taille (15 derniers jours)
        $startWaist = 95.0;
        for ($i = 15; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $waist = $startWaist - ($i * 0.3) + fake()->randomFloat(1, -0.5, 0.5);

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $waistTopic->id,
                'value' => round($waist, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des valeurs pour la distance (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $distance = fake()->randomFloat(1, 3, 10); // 3-10 km par jour

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $distanceTopic->id,
                'value' => $distance,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des valeurs pour les calories in (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $calories = fake()->numberBetween(1800, 2600); // Variation autour du TDEE

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $caloriesInTopic->id,
                'value' => $calories,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des valeurs pour les calories out (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $calories = fake()->numberBetween(2200, 2800); // TDEE + activité

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $caloriesOutTopic->id,
                'value' => $calories,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des valeurs pour le body fat (10 derniers jours)
        $startBodyFat = 22.0;
        for ($i = 10; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $bodyFat = $startBodyFat - ($i * 0.1) + fake()->randomFloat(1, -0.2, 0.2);

            Value::factory()->create([
                'user_id' => $user->id,
                'topic_id' => $bodyFatTopic->id,
                'value' => round($bodyFat, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Créer des objectifs
        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $weightTopic->id,
            'target' => 75.0,
            'type' => GoalTypeEnum::decrease,
            'started_at' => now()->subDays(30),
            'ended_at' => now()->addDays(60),
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $waistTopic->id,
            'target' => 85.0,
            'type' => GoalTypeEnum::decrease,
            'started_at' => now()->subDays(15),
            'ended_at' => now()->addDays(45),
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $distanceTopic->id,
            'target' => 10.0,
            'type' => GoalTypeEnum::increase,
            'started_at' => now()->subDays(20),
            'ended_at' => now()->addDays(40),
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $bodyFatTopic->id,
            'target' => 18.0,
            'type' => GoalTypeEnum::decrease,
            'started_at' => now()->subDays(10),
            'ended_at' => now()->addDays(50),
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $caloriesInTopic->id,
            'target' => 2200.0,
            'type' => GoalTypeEnum::maintain,
            'started_at' => now()->subDays(20),
            'ended_at' => now()->addDays(40),
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $caloriesOutTopic->id,
            'target' => 2600.0,
            'type' => GoalTypeEnum::increase,
            'started_at' => now()->subDays(20),
            'ended_at' => now()->addDays(40),
        ]);

        // Créer des repas
        $mealNames = [
            'Breakfast: Oatmeal with fruits',
            'Lunch: Chicken salad',
            'Dinner: Grilled salmon with vegetables',
            'Snack: Apple and almonds',
            'Breakfast: Scrambled eggs and toast',
            'Lunch: Pasta with tomato sauce',
            'Dinner: Beef stir-fry',
            'Snack: Protein shake',
        ];

        for ($i = 0; $i < 15; $i++) {
            Meal::factory()->create([
                'user_id' => $user->id,
                'ingredients' => fake()->randomElement($mealNames),
                'calories' => fake()->numberBetween(300, 800),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Demo user credentials:');
        $this->command->info('Email: demo@showme.test');
        $this->command->info('Password: password');
    }
}
