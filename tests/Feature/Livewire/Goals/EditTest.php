<?php

declare(strict_types=1);

use App\Enums\GoalTypeEnum;
use App\Livewire\Goals\Edit;
use App\Models\Topic;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->assertStatus(200);
});

test('can see data from goal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create();
    $goal = $topic->goals()->create([
        'name' => 'Test Goal',
        'topic_id' => $topic->id,
        'user_id' => $user->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 100,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ]);

    Livewire::test(Edit::class)
        ->call('edit', $goal->id)
        ->assertSet('name', 'Test Goal')
        ->assertSet('topic_id', $topic->id)
        ->assertSet('type', GoalTypeEnum::decrease->value)
        ->assertSet('target', 100);

    expect($goal->name)->toBe('Test Goal');
    expect($goal->type)->toBe(GoalTypeEnum::decrease);
    expect($goal->target)->toBe(100);
});

test('can update attribute', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create();
    $goal = $topic->goals()->create([
        'name' => 'Test Goal',
        'topic_id' => $topic->id,
        'user_id' => $user->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 100,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ]);

    $attributes = [
        'name' => 'Updated Goal',
        'topic_id' => $topic->id,
        'type' => GoalTypeEnum::decrease,
        'target' => 200,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ];

    Livewire::test(Edit::class)
        ->call('edit', $goal->id)
        ->set('name', $attributes['name'])
        ->set('topic_id', $attributes['topic_id'])
        ->set('type', $attributes['type'])
        ->set('target', $attributes['target'])
        ->set('started_at', $attributes['started_at'])
        ->set('ended_at', $attributes['ended_at'])
        ->call('submit');

    $goal->refresh();

    expect($goal->name)->toBe($attributes['name']);
    expect($goal->topic_id)->toBe($attributes['topic_id']);
    expect($goal->type)->toBe($attributes['type']);
    expect($goal->target)->toBe($attributes['target']);

});
