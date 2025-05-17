<?php

declare(strict_types=1);

use App\Livewire\Meals\Index;
use App\Models\Meal;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});

test('show meals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $meal = Meal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee($meal->ingredients)
        ->assertSee($meal->calories);
});

test('can paginate above 10 meals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Meal::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->assertSee('Showing 1 to 10 of 15 results');
});

test('can go to second page if pagination is available', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Meal::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('gotoPage', 2)
        ->assertSee('Showing 11 to 15 of 15 results');
});

test('can delete meal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $meal = Meal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('delete', $meal->id);

    expect(Meal::find($meal->id))->toBeNull();
});

test('can dispatch edit event', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $meal = Meal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('edit', $meal->id)
        ->assertDispatched('editMeal', $meal->id);
});

test('can relead meals', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('reloadMeals')
        ->assertStatus(200);

});

test('can copy meal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $meal = Meal::factory()->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->call('copy', $meal->id);

    expect(Meal::where('ingredients', $meal->ingredients)->count())->toBe(2);
});

test('can sort table', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Meal::factory()->count(15)->create(['user_id' => $user->id]);

    Livewire::test(Index::class)
        ->set('sortBy', 'ingredients')
        ->set('sortDirection', 'asc')
        ->assertSee(Meal::orderBy('ingredients')->first()->ingredients);

});
