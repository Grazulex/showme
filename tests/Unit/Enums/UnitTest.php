<?php

declare(strict_types=1);

test('check value Calories', function (): void {
    $unit = App\Enums\UnitEnum::calories;

    expect($unit->value)
        ->toEqual('cal');
    expect($unit->label())
        ->toEqual('Calories');
    expect($unit->color())
        ->toEqual('yellow');
    expect($unit->icon())
        ->toEqual('flame');
});

test('check value Kilogram', function (): void {
    $unit = App\Enums\UnitEnum::kilogram;

    expect($unit->value)
        ->toEqual('kg');
    expect($unit->label())
        ->toEqual('Kilogram');
    expect($unit->color())
        ->toEqual('blue');
    expect($unit->icon())
        ->toEqual('weight');
});

test('check value Centimeter', function (): void {
    $unit = App\Enums\UnitEnum::centimeter;

    expect($unit->value)
        ->toEqual('cm');
    expect($unit->label())
        ->toEqual('Centimeter');
    expect($unit->color())
        ->toEqual('green');
    expect($unit->icon())
        ->toEqual('ruler');
});

test('check value Kilometer per hour', function (): void {
    $unit = App\Enums\UnitEnum::kilometerperhour;

    expect($unit->value)
        ->toEqual('km/h');
    expect($unit->label())
        ->toEqual('Kilometer per hour');
    expect($unit->color())
        ->toEqual('red');
    expect($unit->icon())
        ->toEqual('gauge');
});
