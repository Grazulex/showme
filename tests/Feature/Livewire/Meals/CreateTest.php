<?php

declare(strict_types=1);

use App\Actions\Meals\CreateMealAction;
use App\Livewire\Meals\Create;
use App\Models\Configuration;
use App\Models\Topic;
use App\Models\User;

it('creates a meal with valid attributes without configuration', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
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

    $valueSameDay = DB::table('values')
        ->where('user_id', $user->id)
        ->whereDate('created_at', $attributes['created_at']->toDateString())
        ->sum('calories');
    expect($valueSameDay)->toBe(0);
});

it('creates a meal with valid attributes with configuration', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = Topic::factory()->create([
        'user_id' => $user->id,
        'name' => 'Calories',
        'description' => 'Calories in',
        'unit' => App\Enums\UnitEnum::calories,
    ]);

    Configuration::factory()->create([
        'user_id' => $user->id,
        'topic_calorie_in' => $topic->id,
    ]);

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

    $valueSameDay = App\Models\Value::where('user_id', $user->id)
        ->where('topic_id', $topic->id)
        ->whereDate('created_at', $attributes['created_at']->toDateString())
        ->sum('value');
    expect($valueSameDay)->toBe($attributes['calories']);
});

it('creates a meal with valid attributes with configuration and other value', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $topic = Topic::factory()->create([
        'user_id' => $user->id,
        'name' => 'Calories',
        'description' => 'Calories in',
        'unit' => App\Enums\UnitEnum::calories,
    ]);

    Configuration::factory()->create([
        'user_id' => $user->id,
        'topic_calorie_in' => $topic->id,
    ]);

    $action = new CreateMealAction();

    $attributes = [
        'ingredients' => 'Chicken, Rice, Vegetables',
        'calories' => 100,
        'created_at' => now(),
    ];

    $action->handle($user, $attributes);

    $attributes = [
        'ingredients' => 'Chicken, Rice, Vegetables',
        'calories' => 500,
        'created_at' => now(),
    ];

    $meal = $action->handle($user, $attributes);

    $values = App\Models\Value::where('user_id', $user->id)->get();
    expect($values->count())->toBe(1)
        ->and($meal->user_id)->toBe($user->id)
        ->and($meal->ingredients)->toBe($attributes['ingredients'])
        ->and($meal->calories)->toBe($attributes['calories']);

    $valueSameDay = App\Models\Value::where('user_id', $user->id)
        ->where('topic_id', $topic->id)
        ->whereDate('created_at', $attributes['created_at']->toDateString())
        ->sum('value');
    expect($valueSameDay)->toBe($attributes['calories'] + 100);
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

test('can create meal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('ingredients', 'Test Meal')
        ->set('calories', 500)
        ->set('created_at', now())
        ->call('submit');

    $this->assertDatabaseHas('meals', [
        'user_id' => $user->id,
        'ingredients' => 'Test Meal',
        'calories' => 500,
        'created_at' => now()->toDateTimeString(),
    ]);
});
