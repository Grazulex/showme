<?php

declare(strict_types=1);

use App\Models\Configuration;

test('to array', function (): void {
    $configuration = Configuration::factory()->create();

    expect(array_keys($configuration->toArray()))
        ->toEqual([
            'topic_weight',
            'topic_calorie_in',
            'topic_calorie_out',
            'user_id',
            'id',
        ]);
});
