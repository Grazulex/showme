<?php

declare(strict_types=1);

use App\Models\Topic;
use App\Models\User;

test('to array', function () {
    $topic = Topic::factory()->create();

    expect(array_keys($topic->toArray()))
        ->toEqual([
            'name',
            'slug',
            'description',
            'unit',
            'user_id',
            'updated_at',
            'created_at',
            'id',
        ]);
});

test('test relationships', function () {
    $topic = Topic::factory()->create();

    expect($topic->user)
        ->toBeInstanceOf(User::class);
});
