<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GoalTypeEnum;
use App\Enums\UnitEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Jean-Marc Strauven',
            'email' => 'jms@grazulex.be',
        ]);

        $poids = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Perte de poids',
            'description' => 'Perte de poids pour être en meilleure santé.',
            'unit' => UnitEnum::kilogram,
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $poids->id,
            'name' => 'Perdre 10 kg',
            'type' => GoalTypeEnum::decrease,
            'target' => 10,
            'started_at' => now(),
            'ended_at' => now()->addDays(15),
        ]);

        $kilometre = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Kilomètre',
            'description' => 'Kilomètre couru dans par jour.',
            'unit' => UnitEnum::centimeter,
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $kilometre->id,
            'name' => 'Courir 5 km',
            'type' => GoalTypeEnum::increase,
            'target' => 500000,
            'started_at' => now(),
            'ended_at' => now()->addDays(30),
        ]);

        $vitesse = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Vitesse',
            'description' => 'Vitesse moyenne courue dans la journée.',
            'unit' => UnitEnum::kilometerperhour,
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $vitesse->id,
            'name' => 'Courir à 7.5 km/h',
            'type' => GoalTypeEnum::increase,
            'target' => 7.5,
            'started_at' => now(),
            'ended_at' => now()->addDays(30),
        ]);

        $tourdeventre = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Tour de ventre',
            'description' => 'Tour de ventre pour être en meilleure santé.',
            'unit' => UnitEnum::centimeter,
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $tourdeventre->id,
            'name' => 'Perdre 5 cm de tour de ventre',
            'type' => GoalTypeEnum::decrease,
            'target' => 5,
            'started_at' => now(),
            'ended_at' => now()->addDays(30),
        ]);

        $colorie = Topic::factory()->create([
            'user_id' => $user->id,
            'name' => 'Calories',
            'description' => 'Calories dépensées dans la journée.',
            'unit' => UnitEnum::calories,
        ]);

        Goal::factory()->create([
            'user_id' => $user->id,
            'topic_id' => $colorie->id,
            'name' => 'Brûler 2000 calories',
            'type' => GoalTypeEnum::increase,
            'target' => 2000,
            'started_at' => now(),
            'ended_at' => now()->addDays(30),
        ]);
    }
}
