<?php

declare(strict_types=1);

namespace App\Actions\Values;

use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use App\Models\Value;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateValueAction
{
    /**
     * @throws Throwable
     */
    public function handle(Value $value, array $attributes): Value
    {

        $topic = Topic::findOrFail($attributes['topic_id']);
        $last_value = $topic->getLastValueBeforeDate($attributes['created_at']);
        $diff = $last_value ? $attributes['value'] - $last_value->value : 0;
        $color = 'blue';

        $last_goal = $topic->getFirstActiveGoal();
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

        // TODO: Update diif and color next entry

        return DB::transaction(function () use ($value, $attributes, $diff, $color): Value {
            $value->update([
                'topic_id' => $attributes['topic_id'],
                'value' => $attributes['value'],
                'created_at' => $attributes['created_at'],
                'color' => $color,
                'diff_with_last' => $diff,
            ]);

            $value->refresh();

            return $value;
        });
    }
}
