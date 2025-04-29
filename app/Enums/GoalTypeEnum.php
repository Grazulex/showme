<?php

declare(strict_types=1);

namespace App\Enums;

enum GoalTypeEnum: string
{
    case increase = 'increase';
    case decrease = 'decrease';
    case maintain = 'maintain';

    public function label(): string
    {
        return match ($this) {
            self::increase => 'Increase',
            self::decrease => 'Decrease',
            self::maintain => 'Maintain',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::increase => 'arrow-up',
            self::decrease => 'arrow-down',
            self::maintain => 'equals',
        };
    }
}
