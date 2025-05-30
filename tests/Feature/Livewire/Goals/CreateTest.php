<?php

declare(strict_types=1);

use App\Enums\GoalTypeEnum;
use App\Livewire\Goals\Create;
use App\Models\Topic;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('can create goal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create();

    Livewire::test(Create::class)
        ->set('name', 'Test Goal')
        ->set('topic_id', $topic->id)
        ->set('type', GoalTypeEnum::decrease->value)
        ->set('target', 100)
        ->set('started_at', now())
        ->set('ended_at', now()->addDays(30))
        ->call('submit');

    $this->assertDatabaseHas('goals', [
        'name' => 'Test Goal',
        'type' => GoalTypeEnum::decrease->value,
        'target' => 100,
        'started_at' => now(),
        'ended_at' => now()->addDays(30),
    ]);
});

test('need to fill all fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create();

    Livewire::test(Create::class)
        ->set('name', '')
        ->set('topic_id', '')
        ->set('type', '')
        ->call('submit')
        ->assertHasErrors([
            'name',
            'topic_id',
            'type',
        ]);
});
