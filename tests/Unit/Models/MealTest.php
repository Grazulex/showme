<?php

declare(strict_types=1);

use App\Models\Meal;

test('to array', function (): void {
    $meal = Meal::factory()->create();

    expect(array_keys($meal->toArray()))
        ->toEqual([
            'ingredients',
            'calories',
            'created_at',
            'updated_at',
            'user_id',
            'id',
        ]);
});

test('relation with user', function (): void {
    $meal = Meal::factory()->create();

    expect($meal->user)
        ->toBeInstanceOf(App\Models\User::class);
});
