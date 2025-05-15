<?php

declare(strict_types=1);

use App\Actions\Topics\UpdateTopicAction;
use App\Enums\UnitEnum;
use App\Models\Topic;
use App\Models\User;

test('update topic via action', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(['user_id' => $user->id]);

    $attributes = [
        'name' => 'Updated Topic',
        'description' => 'Updated Topic Description',
        'unit' => UnitEnum::centimeter,
    ];

    $action = new UpdateTopicAction();
    $action->handle($topic, $attributes);

    expect($topic->name)->toBe($attributes['name'])
        ->and($topic->description)->toBe($attributes['description'])
        ->and($topic->unit)->toBe($attributes['unit']);

});
