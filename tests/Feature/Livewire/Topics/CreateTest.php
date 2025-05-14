<?php

declare(strict_types=1);

use App\Enums\UnitEnum;
use App\Livewire\Topics\Create;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('can create topic', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('name', 'Test Topic')
        ->set('description', 'This is a test topic.')
        ->set('unit', UnitEnum::calories->value)
        ->set('is_weight', false)
        ->call('submit');

    $this->assertDatabaseHas('topics', [
        'name' => 'Test Topic',
        'description' => 'This is a test topic.',
        'slug' => 'test-topic',
        'is_weight' => false,
        'unit' => UnitEnum::calories->value,
    ]);
});

test('need to fill all fields', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('name', '')
        ->set('description', '')
        ->set('unit', '')
        ->set('is_weight', false)
        ->call('submit')
        ->assertHasErrors(['name', 'description', 'unit']);
});
