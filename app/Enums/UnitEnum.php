<?php

declare(strict_types=1);

namespace App\Enums;

enum UnitEnum: string
{
    case kilogram = 'kg';
    case centimeter = 'cm';
    case kilometer = 'km';
    case kilometerperhour = 'km/h';
    case calories = 'cal';

    case percentage = '%';

    public function label(): string
    {
        return match ($this) {
            self::kilogram => 'Kilogram',
            self::centimeter => 'Centimeter',
            self::kilometer => 'Kilometer',
            self::kilometerperhour => 'Kilometer per hour',
            self::calories => 'Calorie',
            self::percentage => 'Percentage',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::kilogram => 'blue',
            self::centimeter => 'green',
            self::kilometer => 'purple',
            self::kilometerperhour => 'red',
            self::calories => 'yellow',
            self::percentage => 'orange',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::kilogram => 'weight',
            self::centimeter => 'ruler',
            self::kilometer => 'footprints',
            self::kilometerperhour => 'gauge',
            self::calories => 'flame',
            self::percentage => 'percent',
        };
    }
}
