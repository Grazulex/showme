<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\GoalTypeEnum;
use App\Models\Goal;

final class Color
{
    public function getColor(Goal $goal, float $diff): string
    {
        $color = 'blue';
        if ($goal->type === GoalTypeEnum::decrease) {
            if ($diff < 0) {
                $color = 'green';
            } elseif ($diff > 0) {
                $color = 'red';
            }
        }
        if ($goal->type === GoalTypeEnum::increase) {
            if ($diff > 0) {
                $color = 'green';
            } elseif ($diff < 0) {
                $color = 'red';
            }
        }
        if ($goal->type === GoalTypeEnum::maintain) {
            if ($diff > 0) {
                $color = 'red';
            } elseif ($diff < 0) {
                $color = 'red';
            } else {
                $color = 'green';
            }
        }

        return $color;
    }
}
