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

test('relation with user', function (): void {
    $configuration = Configuration::factory()->create();

    expect($configuration->user)
        ->toBeInstanceOf(App\Models\User::class);
});

test('relation with topic', function (): void {
    $configuration = Configuration::factory()->create();

    expect($configuration->topicWeight)
        ->toBeInstanceOf(App\Models\Topic::class)
        ->and($configuration->topicCalorieIn)
        ->toBeInstanceOf(App\Models\Topic::class)
        ->and($configuration->topicCalorieOut)
        ->toBeInstanceOf(App\Models\Topic::class);

});
