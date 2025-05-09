<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Value;
use App\Services\Color;
use Carbon\Carbon;

final class ValueObserver
{
    public function creating(Value $value): void
    {
        $topic = $value->topic;
        $goal = $topic->getFirstActiveGoal();
        $last_value = $topic->getLastValueBeforeDate(Carbon::parse($value->created_at));
        $diff = $last_value ? $value->value - $last_value->value : 0;
        $colorServices = new Color();
        $color = $goal ? $colorServices->getColor($goal, $diff) : 'blue';
        $value->color = $color;
        $value->diff_with_last = $diff;

        $next_value = $topic->getFirstValueAfterDate(Carbon::parse($value->created_at));
        if ($next_value) {
            $diff = $next_value->value - $value->value;
            $color = $goal ? $colorServices->getColor($goal, $diff) : 'blue';
            $next_value::withoutEvents(function () use ($next_value, $diff, $color): void {
                $next_value->update([
                    'diff_with_last' => $diff,
                    'color' => $color,
                ]);
            });
            $next_value->refresh();
        }
    }

    public function updating(Value $value): void
    {
        if ($value->isDirty('value')) {
            $topic = $value->topic;
            $goal = $topic->getFirstActiveGoal();
            $last_value = $topic->getLastValueBeforeDate(Carbon::parse($value->created_at));
            if ($last_value) {
                $diff = $value->value - $last_value->value;
                $colorServices = new Color();
                $color = $goal ? $colorServices->getColor($goal, $diff) : 'blue';
                $value->color = $color;
                $value->diff_with_last = $diff;
            }
            $next_value = $topic->getFirstValueAfterDate(Carbon::parse($value->created_at));
            if ($next_value) {
                $diff = $next_value->value - $value->value;
                $colorServices = new Color();
                $color = $goal ? $colorServices->getColor($goal, $diff) : 'blue';
                $next_value::withoutEvents(function () use ($next_value, $diff, $color): void {
                    $next_value->update([
                        'diff_with_last' => $diff,
                        'color' => $color,
                    ]);
                });
                $next_value->refresh();
            }

        }
    }

    public function deleting(Value $value): void
    {
        $topic = $value->topic;
        $goal = $topic->getFirstActiveGoal();
        $last_value = $topic->getLastValueBeforeDate(Carbon::parse($value->created_at));
        $next_value = $topic->getFirstValueAfterDate(Carbon::parse($value->created_at));
        if ($next_value) {
            $diff = $next_value->value - $last_value->value;
            $colorServices = new Color();
            $color = $goal ? $colorServices->getColor($goal, $diff) : 'blue';
            $next_value::withoutEvents(function () use ($next_value, $diff, $color): void {
                $next_value->update([
                    'diff_with_last' => $diff,
                    'color' => $color,
                ]);
            });
            $next_value->refresh();
        }
    }
}
