<?php

declare(strict_types=1);

use App\Livewire\Dashboard\Resume;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Resume::class)
        ->assertStatus(200);
});
