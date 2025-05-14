<?php

declare(strict_types=1);

use App\Models\Topic;

test('to array', function () {
    $topic = Topic::factory()->create();

    expect(array_keys($topic->toArray()))
        ->toEqual([
            'name',
            'slug',
            'description',
            'unit',
            'user_id',
            'is_weight',
            'updated_at',
            'created_at',
            'id',
        ]);
});
