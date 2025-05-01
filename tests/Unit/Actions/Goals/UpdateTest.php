<?php

declare(strict_types=1);

use App\Actions\Goals\UpdateGoalAction;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\User;

test('update goal via action', function (): void {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(['user_id' => $user->id]);
    $goal = Goal::factory()->create([
        'name' => 'Test Goal',
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 150,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ]);

    $attributes = [
        'name' => 'Updated Goal',
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::increase,
        'target' => 200,
        'started_at' => now()->addDays(1),
        'ended_at' => now()->addDays(31),
    ];

    $action = new UpdateGoalAction();
    $action->handle($goal, $attributes);

    expect($goal->name)->toBe($attributes['name']);
    expect($goal->type)->toBe($attributes['type']);
    expect($goal->target)->toBe($attributes['target']);
});
