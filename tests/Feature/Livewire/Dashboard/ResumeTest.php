<?php

declare(strict_types=1);

use App\Livewire\Dashboard\Resume;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Resume::class)
        ->assertStatus(200);
});
