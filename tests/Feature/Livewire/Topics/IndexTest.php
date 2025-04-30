<?php

declare(strict_types=1);

use App\Livewire\Topics\Index;
use App\Models\Topic;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});

test('show topcis', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = Topic::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee($topic->name)
        ->assertSee($topic->description);
});

test('can paginate above 10 topics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Topic::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee('Showing 1 to 10 of 15 results');
});

test('can go to second page if pagination is available', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Topic::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('gotoPage', 2)
        ->assertSee('Showing 11 to 15 of 15 results');
});

test('can delete topic', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = Topic::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('delete', $topic->id);

    expect(Topic::find($topic->id))->toBeNull();
});

test('can dispatch edit event', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = Topic::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('edit', $topic->id)
        ->assertDispatched('editTopic', $topic->id);
});

test('can reload topics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('reloadTopics')
        ->assertStatus(200);
});
