<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GoalTypeEnum;
use App\Enums\UnitEnum;
use App\Models\Configuration;
use App\Models\Goal;
use App\Models\Meal;
use App\Models\Topic;
use App\Models\User;
use App\Models\Value;
use Illuminate\Database\Seeder;

final class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds for a specific user email.
     */
    public function run(): void
    {
        // Demander l'email de l'utilisateur
        $email = $this->command->ask('Enter user email', 'demo@showme.test');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command->error("User with email {$email} not found!");

            return;
        }

        $this->command->info("Creating data for user: {$user->name} ({$user->email})");

        // Créer les topics principaux
        $weightTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'weight'],
            [
                'name' => 'Weight',
                'description' => 'Body weight tracking',
                'unit' => UnitEnum::kilogram,
                'is_weight' => true,
            ]
        );

        $waistTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'waist-size'],
            [
                'name' => 'Waist Size',
                'description' => 'Waist circumference',
                'unit' => UnitEnum::centimeter,
                'is_weight' => false,
            ]
        );

        $distanceTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'distance'],
            [
                'name' => 'Distance',
                'description' => 'Distance walked/run',
                'unit' => UnitEnum::kilometer,
                'is_weight' => false,
            ]
        );

        $caloriesInTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'calories-in'],
            [
                'name' => 'Calories In',
                'description' => 'Daily calorie intake',
                'unit' => UnitEnum::calories,
                'is_weight' => false,
            ]
        );

        $caloriesOutTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'calories-out'],
            [
                'name' => 'Calories Out',
                'description' => 'Daily calories burned',
                'unit' => UnitEnum::calories,
                'is_weight' => false,
            ]
        );

        $bodyFatTopic = Topic::firstOrCreate(
            ['user_id' => $user->id, 'slug' => 'body-fat'],
            [
                'name' => 'Body Fat',
                'description' => 'Body fat percentage',
                'unit' => UnitEnum::percentage,
                'is_weight' => false,
            ]
        );

        $this->command->info('Topics created: 6');

        // Créer des configurations système
        Configuration::updateOrCreate(
            ['user_id' => $user->id, 'key' => 'weight_topic_id'],
            ['value' => (string) $weightTopic->id]
        );

        Configuration::updateOrCreate(
            ['user_id' => $user->id, 'key' => 'calories_in_topic_id'],
            ['value' => (string) $caloriesInTopic->id]
        );

        Configuration::updateOrCreate(
            ['user_id' => $user->id, 'key' => 'calories_out_topic_id'],
            ['value' => (string) $caloriesOutTopic->id]
        );

        $this->command->info('Configurations created: 3');

        // Créer des valeurs pour le poids (30 derniers jours, tendance à la baisse)
        $startWeight = 85.0;
        $weightCount = 0;
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weight = $startWeight - ($i * 0.15) + fake()->randomFloat(1, -0.3, 0.3);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $weightTopic->id,
                'value' => round($weight, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $weightCount++;
        }
        $this->command->info("Weight values created: {$weightCount}");

        // Créer des valeurs pour le tour de taille (15 derniers jours)
        $startWaist = 95.0;
        $waistCount = 0;
        for ($i = 15; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $waist = $startWaist - ($i * 0.3) + fake()->randomFloat(1, -0.5, 0.5);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $waistTopic->id,
                'value' => round($waist, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $waistCount++;
        }
        $this->command->info("Waist values created: {$waistCount}");

        // Créer des valeurs pour la distance (20 derniers jours)
        $distanceCount = 0;
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $distance = fake()->randomFloat(1, 3, 10);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $distanceTopic->id,
                'value' => $distance,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $distanceCount++;
        }
        $this->command->info("Distance values created: {$distanceCount}");

        // Créer des valeurs pour les calories in (20 derniers jours)
        $caloriesInCount = 0;
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $calories = fake()->numberBetween(1800, 2600);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $caloriesInTopic->id,
                'value' => $calories,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $caloriesInCount++;
        }
        $this->command->info("Calories In values created: {$caloriesInCount}");

        // Créer des valeurs pour les calories out (20 derniers jours)
        $caloriesOutCount = 0;
        for ($i = 20; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $calories = fake()->numberBetween(2200, 2800);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $caloriesOutTopic->id,
                'value' => $calories,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $caloriesOutCount++;
        }
        $this->command->info("Calories Out values created: {$caloriesOutCount}");

        // Créer des valeurs pour le body fat (10 derniers jours)
        $startBodyFat = 22.0;
        $bodyFatCount = 0;
        for ($i = 10; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $bodyFat = $startBodyFat - ($i * 0.1) + fake()->randomFloat(1, -0.2, 0.2);

            Value::create([
                'user_id' => $user->id,
                'topic_id' => $bodyFatTopic->id,
                'value' => round($bodyFat, 1),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $bodyFatCount++;
        }
        $this->command->info("Body Fat values created: {$bodyFatCount}");

        // Créer des objectifs
        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $weightTopic->id],
            [
                'target' => 75.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(30),
                'ended_at' => now()->addDays(60),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $waistTopic->id],
            [
                'target' => 85.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(15),
                'ended_at' => now()->addDays(45),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $distanceTopic->id],
            [
                'target' => 10.0,
                'type' => GoalTypeEnum::increase,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $bodyFatTopic->id],
            [
                'target' => 18.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(10),
                'ended_at' => now()->addDays(50),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $caloriesInTopic->id],
            [
                'target' => 2200.0,
                'type' => GoalTypeEnum::maintain,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $caloriesOutTopic->id],
            [
                'target' => 2600.0,
                'type' => GoalTypeEnum::increase,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        $this->command->info('Goals created: 6');

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

        $mealCount = 0;
        for ($i = 0; $i < 15; $i++) {
            Meal::create([
                'user_id' => $user->id,
                'ingredients' => fake()->randomElement($mealNames),
                'calories' => fake()->numberBetween(300, 800),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
            $mealCount++;
        }
        $this->command->info("Meals created: {$mealCount}");

        $this->command->info('✅ Data seeded successfully for user: '.$user->email);
    }
}
