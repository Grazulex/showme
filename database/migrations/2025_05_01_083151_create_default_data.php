<?php

declare(strict_types=1);

use App\Enums\GoalTypeEnum;
use App\Enums\UnitEnum;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->insert([
            ['name' => 'Jean-Marc Strauve', 'email' => 'jms@grazulex.be', 'password' => Hash::make('password')],
        ]);
        $user_id = User::where('email', 'jms@grazulex.be')->select('id')->first()->id;

        DB::table('topics')->insert([
            ['name' => 'Poids', 'slug' => Str::slug('Poids'), 'description' => 'Perte de poids pour être en meilleur form', 'unit' => UnitEnum::kilogram, 'user_id' => $user_id],
            ['name' => 'Distance', 'slug' => Str::slug('Distance'), 'description' => 'Courir de plus grande distance', 'unit' => UnitEnum::kilometer, 'user_id' => $user_id],
            ['name' => 'Vitesse', 'slug' => Str::slug('Vitesse'), 'description' => 'Vitesse lors des courses', 'unit' => UnitEnum::kilometerperhour, 'user_id' => $user_id],
            ['name' => 'Tour de Taille', 'slug' => Str::slug('Tour de Taille'), 'description' => 'Réduire le tour de taille', 'unit' => UnitEnum::centimeter, 'user_id' => $user_id],
            ['name' => 'Calories', 'slug' => Str::slug('Calories'), 'description' => 'Contrôle des calories brulées par jour', 'unit' => UnitEnum::calories, 'user_id' => $user_id],
        ]);

        $poids_id = Topic::where('name', 'Poids')->select('id')->first()->id;
        $distance_id = Topic::where('name', 'Distance')->select('id')->first()->id;
        $vitesse_id = Topic::where('name', 'Vitesse')->select('id')->first()->id;
        $tourtaille_id = Topic::where('name', 'Tour de Taille')->select('id')->first()->id;
        $calories_id = Topic::where('name', 'Calories')->select('id')->first()->id;

        DB::table('goals')->insert([
            ['name' => 'Perdre 10kg en 30 jours', 'user_id' => $user_id, 'topic_id' => $poids_id, 'type' => GoalTypeEnum::decrease, 'target' => 140.0, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
            ['name' => 'Courir 5km dans 30 jours', 'user_id' => $user_id, 'topic_id' => $distance_id, 'type' => GoalTypeEnum::increase, 'target' => 5, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
            ['name' => 'Augmenter de course sa vitesse à 7.5km/h', 'user_id' => $user_id, 'topic_id' => $vitesse_id, 'type' => GoalTypeEnum::increase, 'target' => 7.5, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
            ['name' => 'Perde 5cm de tour de taille en 30 jours', 'user_id' => $user_id, 'topic_id' => $tourtaille_id, 'type' => GoalTypeEnum::decrease, 'taget' => 140.0, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
            ['name' => 'Bruler 500 calories par jour', 'user_id' => $user_id, 'topic_id' => $calories_id, 'type' => GoalTypeEnum::maintain, 'target' => 500, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
        ]);
    }
};
