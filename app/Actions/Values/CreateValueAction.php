<?php

declare(strict_types=1);

namespace App\Actions\Values;

use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use App\Models\User;
use App\Models\Value;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

final class CreateValueAction
{
    /**
     * @param User $user
     * @param array $attributes
     * @return Value
     * @throws Throwable
     */
    public function handle(User $user, array $attributes): Value
    {
        $topic = Topic::findOrFail($attributes['topic_id']);
        $last_value = $topic->getLastValueBeforeDate($attributes['created_at']);
        $diff = $last_value ? $attributes['value'] - $last_value->value : 0;
        $color = 'blue';

        $last_goal = $topic->getFirstActifGoal();
        if ($last_goal) {
            if ($last_goal->type === GoalTypeEnum::decrease) {
                if ($diff < 0) {
                    $color = 'green';
                } elseif ($diff > 0) {
                    $color = 'red';
                }
            }
            if ($last_goal->type === GoalTypeEnum::increase) {
                if ($diff > 0) {
                    $color = 'green';
                } elseif ($diff < 0) {
                    $color = 'red';
                } else {
                    $color = 'blue';
                }
            }
            if ($last_goal->type === GoalTypeEnum::maintain) {
                if ($diff > 0) {
                    $color = 'red';
                } elseif ($diff < 0) {
                    $color = 'red';
                } else {
                    $color = 'green';
                }
            }
        }

        return DB::transaction(function () use ($user, $attributes, $diff, $color) {
            return Value::create([
                'user_id' => $user->id,
                'topic_id' => $attributes['topic_id'],
                'value' => $attributes['value'],
                'diff_with_last' => $diff,
                'color' => $color,
                'created_at' => $attributes['created_at'],
            ]);
        });
    }
}
