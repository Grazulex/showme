<?php

declare(strict_types=1);

namespace App\Actions\Goals;

use App\Models\Goal;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateGoalAction
{
    /**
     * @param Goal $goal
     * @param array $attributes
     * @return Goal
     * @throws Throwable
     */
    public function handle(Goal $goal, array $attributes): Goal
    {
        return DB::transaction(function () use ($goal, $attributes): Goal {
            $goal->update(
                [
                    'name' => $attributes['name'],
                    'topic_id' => $attributes['topic_id'],
                    'type' => $attributes['type'],
                    'target' => $attributes['target'],
                    'started_at' => $attributes['started_at'],
                    'ended_at' => $attributes['ended_at'],
                ]
            );

            $goal->refresh();

            return $goal;
        });
    }
}
