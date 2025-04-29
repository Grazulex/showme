<?php

declare(strict_types=1);

test('check value increase', function (): void {
    $goalType = App\Enums\GoalTypeEnum::increase;

    expect($goalType->value)
        ->toEqual('increase');
    expect($goalType->label())
        ->toEqual('Increase');
    expect($goalType->icon())
        ->toEqual('arrow-up');
});

test('check value decrease', function (): void {
    $goalType = App\Enums\GoalTypeEnum::decrease;

    expect($goalType->value)
        ->toEqual('decrease');
    expect($goalType->label())
        ->toEqual('Decrease');
    expect($goalType->icon())
        ->toEqual('arrow-down');
});

test('check value maintain', function (): void {
    $goalType = App\Enums\GoalTypeEnum::maintain;

    expect($goalType->value)
        ->toEqual('maintain');
    expect($goalType->label())
        ->toEqual('Maintain');
    expect($goalType->icon())
        ->toEqual('equals');
});
