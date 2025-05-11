<?php

declare(strict_types=1);

use App\Models\Topic;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
            'birth_at',
            'height',
            'calories_each_day',
        ]);
});

it('may have topics', function (): void {
    $user = User::factory()->create();
    $user->topics()->saveMany([
        Topic::factory()->make(),
        Topic::factory()->make(),
    ]);

    expect($user->topics)
        ->toHaveCount(2);
});
