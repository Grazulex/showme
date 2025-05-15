<?php

declare(strict_types=1);

use App\Actions\Meals\CreateMealAction;
use App\Livewire\Meals\Create;
use App\Models\User;

it('creates a meal with valid attributes', function () {
    $user = User::factory()->create();
    $attributes = [
        'ingredients' => 'Chicken, Rice, Vegetables',
        'calories' => 500,
        'created_at' => now(),
    ];

    $action = new CreateMealAction();

    $meal = $action->handle($user, $attributes);

    expect($meal->user_id)->toBe($user->id)
        ->and($meal->ingredients)->toBe($attributes['ingredients'])
        ->and($meal->calories)->toBe($attributes['calories']);
});

test('need to fill all fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('ingredients', '')
        ->set('calories', null)
        ->call('submit')
        ->assertHasErrors([
            'ingredients',
        ]);
});
