<?php

declare(strict_types=1);

use App\Actions\Values\UpdateValueAction;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\User;
use App\Models\Value;

test('update Value via action on decrease goal', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(['user_id' => $user->id]);
    $goal = Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 150,
    ]);

    $value = Value::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'value' => 100,
        'created_at' => now(),
    ]);

    $attributes = [
        'value' => 200,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('red')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 50,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('green')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 50,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('blue')
        ->and($value->user_id)->toBe($user->id);
});

test('update Value via action on increase goal', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(['user_id' => $user->id]);
    $goal = Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::increase,
        'target' => 150,
    ]);

    $value = Value::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'value' => 100,
        'created_at' => now(),
    ]);

    $attributes = [
        'value' => 200,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('green')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 50,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('red')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 50,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('blue')
        ->and($value->user_id)->toBe($user->id);
});

test('update Value via action on maintain goal', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(['user_id' => $user->id]);
    $goal = Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::maintain,
        'target' => 150,
    ]);

    $value = Value::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'value' => 100,
        'created_at' => now(),
    ]);

    $attributes = [
        'value' => 200,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('red')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 50,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('red')
        ->and($value->user_id)->toBe($user->id);

    $attributes = [
        'value' => 200,
        'topic_id' => $topic->id,
        'created_at' => now(),
    ];

    $action = new UpdateValueAction();
    $action->handle($value, $attributes);

    expect($value->value)->toBe(number_format($attributes['value'], 2))
        ->and($value->topic_id)->toBe($topic->id)
        ->and($value->color)->toBe('red')
        ->and($value->user_id)->toBe($user->id);
});
