<?php

declare(strict_types=1);

use App\Livewire\Goals\Index;
use App\Models\Goal;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});

test('show goals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $goal = Goal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee($goal->name)
        ->assertSee($goal->description);
});

test('can paginate above 10 goals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Goal::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee('Showing 1 to 10 of 15 results');
});

test('can go to second page if pagination is available', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Goal::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('gotoPage', 2)
        ->assertSee('Showing 11 to 15 of 15 results');
});

test('can delete goal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $goal = Goal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('delete', $goal->id);

    expect(Goal::find($goal->id))->toBeNull();
});

test('can dispatch edit event', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $goal = Goal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('edit', $goal->id)
        ->assertDispatched('editGoal', $goal->id);
});

test('can relead goals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('reloadGoals')
        ->assertStatus(200);

});
