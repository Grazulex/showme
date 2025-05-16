<?php

declare(strict_types=1);

use App\Models\Goal;
use App\Models\User;

test('to array', function (): void {
    $goal = Goal::factory()->create();

    expect(array_keys($goal->toArray()))
        ->toEqual([
            'name',
            'topic_id',
            'user_id',
            'type',
            'target',
            'started_at',
            'ended_at',
            'updated_at',
            'created_at',
            'id',
        ]);
});

test('relation with user', function (): void {
    $goal = Goal::factory()->create();

    expect($goal->user)
        ->toBeInstanceOf(User::class);
});
