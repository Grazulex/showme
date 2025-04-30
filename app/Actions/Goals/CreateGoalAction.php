<?php

declare(strict_types=1);

namespace App\Actions\Goals;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CreateGoalAction
{
    public function handle(User $user, array $attributes): Goal
    {
        return DB::transaction(function () use ($user, $attributes) {
            return Goal::create(
                [
                    'user_id' => $user->id,
                    'topic_id' => $attributes['topic_id'],
                    'name' => $attributes['name'],
                    'type' => $attributes['type'],
                    'target' => $attributes['target'],
                    'started_at' => $attributes['started_at'],
                    'ended_at' => $attributes['ended_at'],
                ]
            );
        });
    }
}
