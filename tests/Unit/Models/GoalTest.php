<?php

declare(strict_types=1);

use App\Models\Goal;

test('to array', function (): void {
    $goal = Goal::factory()->create();

    expect(array_keys($goal->toArray()))
        ->toEqual([
            'name',
            'topic_id',
            'user_id',
            'type',
            'target',
            'ended_at',
            'updated_at',
            'created_at',
            'id',
        ]);
});
