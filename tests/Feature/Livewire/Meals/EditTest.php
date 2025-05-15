<?php

declare(strict_types=1);

use App\Livewire\Meals\Edit;
use App\Models\Meal;
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
    $meal = Meal::create([
        'ingredients' => 'Chicken, Rice, Vegetables',
        'calories' => 500,
        'created_at' => now(),
        'user_id' => $user->id,
    ]);

    Livewire::test(Edit::class)
        ->call('edit', $meal->id)
        ->assertSet('ingredients', 'Chicken, Rice, Vegetables')
        ->assertSet('calories', 500);

    expect($meal->ingredients)->toBe('Chicken, Rice, Vegetables');
    expect($meal->calories)->toBe(500);
});

test('can update attribute', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create();
    $meal = Meal::create([
        'ingredients' => 'Chicken, Rice, Vegetables',
        'calories' => 500,
        'created_at' => now(),
        'user_id' => $user->id,
    ]);

    $attributes = [
        'ingredients' => 'Updated Ingredients',
        'calories' => 600,
        'created_at' => now(),
    ];

    Livewire::test(Edit::class)
        ->call('edit', $meal->id)
        ->set('ingredients', $attributes['ingredients'])
        ->set('calories', $attributes['calories'])
        ->call('submit');

    $meal->refresh();

    expect($meal->ingredients)->toBe($attributes['ingredients'])
        ->and($meal->calories)->toBe($attributes['calories']);

});
