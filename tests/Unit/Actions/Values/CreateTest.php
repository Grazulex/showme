<?php

declare(strict_types=1);

use App\Models\Goal;
use App\Models\Topic;
use App\Models\User;
use App\Models\Value;

test('create value via action', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create([
        'user_id' => $user->id,
    ]);
    $goal = Goal::factory()->create([
        'topic_id' => $topic->id,
        'user_id' => $user->id,
        'type' => App\Enums\GoalTypeEnum::decrease,
    ]);

    $attributes = [
        'value' => 100,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new App\Actions\Values\CreateValueAction();
    $value = $action->handle($user, $attributes);

    expect($value)->toBeInstanceOf(Value::class)
        ->and($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($attributes['topic_id']);

});
