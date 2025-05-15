<?php

declare(strict_types=1);

use App\Models\Value;

test('to array', function (): void {
    $value = Value::factory()->create();

    expect(array_keys($value->toArray()))
        ->toEqual([
            'user_id',
            'topic_id',
            'value',
            'diff_with_last',
            'color',
            'updated_at',
            'created_at',
            'id',
            'topic',
        ]);
});
