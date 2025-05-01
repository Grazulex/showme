<?php

declare(strict_types=1);

use App\Actions\Goals\CreateGoalAction;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\User;

test('create goal via action', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create([
        'user_id' => $user->id,
    ]);

    $attributes = [
        'name' => 'Test Goal',
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 150,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ];

    $action = new CreateGoalAction();
    $goal = $action->handle($user, $attributes);

    expect($goal)->toBeInstanceOf(Goal::class);
    expect($goal->name)->toBe($attributes['name']);
    expect($goal->topic_id)->toBe($attributes['topic_id']);
    expect($goal->type)->toBe($attributes['type']);
    expect($goal->target)->toBe($attributes['target']);
    expect($goal->started_at)->toBe($attributes['started_at']);
    expect($goal->ended_at)->toBe($attributes['ended_at']);
});
