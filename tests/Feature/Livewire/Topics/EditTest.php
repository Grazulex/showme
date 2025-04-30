<?php

declare(strict_types=1);

use App\Enums\UnitEnum;
use App\Livewire\Topics\Edit;
use App\Livewire\Topics\Index;
use App\Models\Topic;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Edit::class)
        ->assertStatus(200);
});

test('can see data from topic', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->create(
        [
            'user_id' => $user->id,
            'name' => 'Test Topic',
            'description' => 'Test Topic Description',
            'unit' => UnitEnum::calories,
        ]
    );

    Livewire::test(Edit::class)
        ->call('edit', $topic->id)
        ->assertSet('name', 'Test Topic')
        ->assertSet('description', 'Test Topic Description')
        ->assertSet('unit', UnitEnum::calories->value);

    expect($topic->name)->toBe('Test Topic');
    expect($topic->description)->toBe('Test Topic Description');
    expect($topic->unit)->toBe(UnitEnum::calories);
});

test('can update attribute', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create(['user_id' => $user->id]);

    $attributes = [
        'name' => 'Updated Topic',
        'description' => 'Updated Topic Description',
        'unit' => UnitEnum::centimeter,
    ];

    Livewire::test(Edit::class)
        ->set('topicId', $topic->id)
        ->set('name', $attributes['name'])
        ->set('description', $attributes['description'])
        ->set('unit', $attributes['unit'])
        ->call('submit');

    $topic->refresh();

    expect($topic->name)->toBe($attributes['name']);
    expect($topic->description)->toBe($attributes['description']);
    expect($topic->unit)->toBe($attributes['unit']);
});

test('show updated data in list after submit', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $topic = Topic::factory()->create(['user_id' => $user->id]);

    $attributes = [
        'name' => 'Updated Topic',
        'description' => 'Updated Topic Description',
        'unit' => UnitEnum::centimeter,
    ];

    Livewire::test(Edit::class)
        ->set('topicId', $topic->id)
        ->set('name', $attributes['name'])
        ->set('description', $attributes['description'])
        ->set('unit', $attributes['unit'])
        ->call('submit');

    Livewire::test(Index::class)
        ->assertSee($attributes['name'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['unit']);
});
