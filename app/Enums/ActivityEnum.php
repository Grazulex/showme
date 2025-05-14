<?php

declare(strict_types=1);

namespace App\Enums;

enum ActivityEnum: string
{
    case SEDENTARY = '1.2';
    case LIGHTLY_ACTIVE = '1.375';
    case MODERATELY_ACTIVE = '1.55';
    case VERY_ACTIVE = '1.725';
    case EXTRA_ACTIVE = '1.9';

    public function label(): string
    {
        return match ($this) {
            self::SEDENTARY => 'Sedentary (little or no exercise)',
            self::LIGHTLY_ACTIVE => 'Lightly active (light exercise/sports 1-3 days/week)',
            self::MODERATELY_ACTIVE => 'Moderately active (moderate exercise/sports 3-5 days/week)',
            self::VERY_ACTIVE => 'Very active (hard exercise/sports 6-7 days a week)',
            self::EXTRA_ACTIVE => 'Extra active (very hard exercise/physical job & exercise 2x/day)',
        };
    }
}
