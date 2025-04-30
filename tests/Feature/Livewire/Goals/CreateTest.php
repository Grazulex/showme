<?php

declare(strict_types=1);

use App\Livewire\Goals\Create;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->assertStatus(200);
});
