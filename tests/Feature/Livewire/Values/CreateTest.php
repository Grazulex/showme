<?php

declare(strict_types=1);

use App\Livewire\Values\Create;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('can create value', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = App\Models\Topic::factory()->create([
        'user_id' => $user->id,
    ]);

    $goal = App\Models\Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
    ]);

    Livewire::test(Create::class)
        ->set('topic_id', $topic->id)
        ->set('value', 100)
        ->set('created_at', '2020-01-01')
        ->call('submit');

    expect(DB::table('values')
        ->where('topic_id', $topic->id)
        ->where('value', 100)
        ->count())->toBe(1);
});

test('can create value before', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = App\Models\Topic::factory()->create([
        'user_id' => $user->id,
    ]);

    App\Models\Goal::factory()->create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
    ]);

    Livewire::test(Create::class)
        ->set('topic_id', $topic->id)
        ->set('value', 100)
        ->set('created_at', '2020-01-01')
        ->call('submit');

    Livewire::test(Create::class)
        ->set('topic_id', $topic->id)
        ->set('value', 200)
        ->set('created_at', '2019-01-01')
        ->call('submit');

    expect(DB::table('values')
        ->where('topic_id', $topic->id)
        ->where('value', 100)
        ->count())->toBe(1);

    expect(DB::table('values')
        ->where('topic_id', $topic->id)
        ->where('value', 200)
        ->count())->toBe(1);
});
