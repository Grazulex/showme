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
        'is_weight' => true,
    ];

    $action = new CreateTopicAction();
    $topic = $action->handle($user, $attributes);

    expect($topic)->toBeInstanceOf(Topic::class)
        ->and($topic->name)->toBe($attributes['name'])
        ->and($topic->description)->toBe($attributes['description'])
        ->and($topic->unit)->toBe($attributes['unit'])
        ->and($topic->user_id)->toBe($user->id)
        ->and($topic->slug)->toBe('test-topic')
        ->and($topic->is_weight)->toBe($attributes['is_weight']);

});
