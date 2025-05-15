<?php

declare(strict_types=1);

use App\Enums\GoalTypeEnum;
use App\Enums\UnitEnum;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $user_id = User::where('email', 'jms@grazulex.be')->select('id')->first()->id;

        DB::table('topics')->insert([
            ['name' => 'Calories IN', 'slug' => Str::slug('Calories_in'), 'description' => 'Contrôle des calories mangées par jour', 'unit' => UnitEnum::calories, 'user_id' => $user_id],
        ]);

        $calories_id = Topic::where('name', 'Calories IN')->select('id')->first()->id;

        DB::table('goals')->insert([
            ['name' => 'Manger 2000 calories par jour', 'user_id' => $user_id, 'topic_id' => $calories_id, 'type' => GoalTypeEnum::maintain, 'target' => 2500, 'started_at' => now(), 'ended_at' => now()->addDays(30)],
        ]);
    }
};
