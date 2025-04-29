<?php

declare(strict_types=1);

use App\Actions\Topics\CreateTopicAction;
use App\Enums\UnitEnum;
use App\Models\Topic;
use App\Models\User;

test('create topic via action', function (): void {
    $user = User::factory()->create();

    $attributes = [
        'name' => 'Test Topic',
        'description' => 'Test Topic Description',
        'unit' => UnitEnum::kilogram,
    ];

    $action = new CreateTopicAction();
    $topic = $action->handle($user, $attributes);

    expect($topic)->toBeInstanceOf(Topic::class);
    expect($topic->name)->toBe($attributes['name']);
    expect($topic->description)->toBe($attributes['description']);
    expect($topic->unit)->toBe($attributes['unit']);
    expect($topic->user_id)->toBe($user->id);
    expect($topic->slug)->toBe('test-topic');

});
