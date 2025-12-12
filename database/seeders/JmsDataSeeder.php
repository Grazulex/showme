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

final class JmsDataSeeder extends Seeder
{
    /**
     * Run the database seeds for jms@grazulex.be.
     */
    public function run(): void
    {
        $email = 'jms@grazulex.be';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command->error("User with email {$email} not found! Please create the account first.");

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

        $this->command->info('✓ Topics created: 6');

        // Créer des configurations système
        Configuration::updateOrCreate(
            ['user_id' => $user->id],
            [
                'topic_weight' => $weightTopic->id,
                'topic_calorie_in' => $caloriesInTopic->id,
                'topic_calorie_out' => $caloriesOutTopic->id,
            ]
        );

        $this->command->info('✓ Configuration created');

        // Créer des valeurs pour le poids (30 derniers jours)
        $startWeight = 85.0;
        for ($i = 30; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $weightTopic->id,
                'value' => round($startWeight - ($i * 0.15) + fake()->randomFloat(1, -0.3, 0.3), 1),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Weight values created: 31');

        // Créer des valeurs pour le tour de taille (15 derniers jours)
        $startWaist = 95.0;
        for ($i = 15; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $waistTopic->id,
                'value' => round($startWaist - ($i * 0.3) + fake()->randomFloat(1, -0.5, 0.5), 1),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Waist values created: 16');

        // Créer des valeurs pour la distance (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $distanceTopic->id,
                'value' => fake()->randomFloat(1, 3, 10),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Distance values created: 21');

        // Créer des valeurs pour les calories in (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $caloriesInTopic->id,
                'value' => fake()->numberBetween(1800, 2600),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Calories In values created: 21');

        // Créer des valeurs pour les calories out (20 derniers jours)
        for ($i = 20; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $caloriesOutTopic->id,
                'value' => fake()->numberBetween(2200, 2800),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Calories Out values created: 21');

        // Créer des valeurs pour le body fat (10 derniers jours)
        $startBodyFat = 22.0;
        for ($i = 10; $i >= 0; $i--) {
            Value::create([
                'user_id' => $user->id,
                'topic_id' => $bodyFatTopic->id,
                'value' => round($startBodyFat - ($i * 0.1) + fake()->randomFloat(1, -0.2, 0.2), 1),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Body Fat values created: 11');

        // Créer des objectifs
        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $weightTopic->id],
            [
                'name' => 'Weight Loss Goal',
                'target' => 75.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(30),
                'ended_at' => now()->addDays(60),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $waistTopic->id],
            [
                'name' => 'Waist Reduction Goal',
                'target' => 85.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(15),
                'ended_at' => now()->addDays(45),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $distanceTopic->id],
            [
                'name' => 'Distance Goal',
                'target' => 10.0,
                'type' => GoalTypeEnum::increase,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $bodyFatTopic->id],
            [
                'name' => 'Body Fat Reduction Goal',
                'target' => 18.0,
                'type' => GoalTypeEnum::decrease,
                'started_at' => now()->subDays(10),
                'ended_at' => now()->addDays(50),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $caloriesInTopic->id],
            [
                'name' => 'Maintain Calorie Intake',
                'target' => 2200.0,
                'type' => GoalTypeEnum::maintain,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        Goal::updateOrCreate(
            ['user_id' => $user->id, 'topic_id' => $caloriesOutTopic->id],
            [
                'name' => 'Increase Calorie Burn',
                'target' => 2600.0,
                'type' => GoalTypeEnum::increase,
                'started_at' => now()->subDays(20),
                'ended_at' => now()->addDays(40),
            ]
        );

        $this->command->info('✓ Goals created: 6');

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
            Meal::create([
                'user_id' => $user->id,
                'ingredients' => fake()->randomElement($mealNames),
                'calories' => fake()->numberBetween(300, 800),
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ]);
        }
        $this->command->info('✓ Meals created: 15');

        $this->command->info('');
        $this->command->info('✅ Data seeded successfully for user: '.$user->email);
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- 6 Topics (Weight, Waist, Distance, Calories In/Out, Body Fat)');
        $this->command->info('- 121 Values across all topics');
        $this->command->info('- 6 Goals (all topics have active goals)');
        $this->command->info('- 15 Meals');
        $this->command->info('- 1 Configuration');
    }
}
