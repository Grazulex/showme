<?php

declare(strict_types=1);

use App\Livewire\Values\Index;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});
