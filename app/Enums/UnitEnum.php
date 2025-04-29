<?php

declare(strict_types=1);

namespace App\Enums;

enum UnitEnum: string
{
    case kilogram = 'kg';
    case centimeter = 'cm';
    case kilometerperhour = 'km/h';
    case calories = 'cal';

    public function label(): string
    {
        return match ($this) {
            self::kilogram => 'Kilogram',
            self::centimeter => 'Centimeter',
            self::kilometerperhour => 'Kilometer per hour',
            self::calories => 'Calories',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::kilogram => 'bg-blue-500',
            self::centimeter => 'bg-green-500',
            self::kilometerperhour => 'bg-red-500',
            self::calories => 'bg-yellow-500',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::kilogram => 'fa-solid fa-weight-hanging',
            self::centimeter => 'fa-solid fa-ruler',
            self::kilometerperhour => 'fa-solid fa-car',
            self::calories => 'fa-solid fa-fire',
        };
    }
}
