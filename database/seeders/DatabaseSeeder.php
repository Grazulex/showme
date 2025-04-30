<?php

declare(strict_types=1);

namespace Database\Seeders;

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

        Topic::factory(20)->create([
            'user_id' => $user->id,
        ]);

        foreach (Topic::all() as $topic) {
            Goal::factory()->create([
                'topic_id' => $topic->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
