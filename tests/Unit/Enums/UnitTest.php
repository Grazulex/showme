<?php

declare(strict_types=1);

test('check value Calories', function (): void {
    $unit = App\Enums\UnitEnum::calories;

    expect($unit->value)
        ->toEqual('cal');
    expect($unit->label())
        ->toEqual('Calories');
    expect($unit->color())
        ->toEqual('bg-yellow-500');
    expect($unit->icon())
        ->toEqual('fa-solid fa-fire');
});

test('check value Kilogram', function (): void {
    $unit = App\Enums\UnitEnum::kilogram;

    expect($unit->value)
        ->toEqual('kg');
    expect($unit->label())
        ->toEqual('Kilogram');
    expect($unit->color())
        ->toEqual('bg-blue-500');
    expect($unit->icon())
        ->toEqual('fa-solid fa-weight-hanging');
});

test('check value Centimeter', function (): void {
    $unit = App\Enums\UnitEnum::centimeter;

    expect($unit->value)
        ->toEqual('cm');
    expect($unit->label())
        ->toEqual('Centimeter');
    expect($unit->color())
        ->toEqual('bg-green-500');
    expect($unit->icon())
        ->toEqual('fa-solid fa-ruler');
});

test('check value Kilometer per hour', function (): void {
    $unit = App\Enums\UnitEnum::kilometerperhour;

    expect($unit->value)
        ->toEqual('km/h');
    expect($unit->label())
        ->toEqual('Kilometer per hour');
    expect($unit->color())
        ->toEqual('bg-red-500');
    expect($unit->icon())
        ->toEqual('fa-solid fa-car');
});
