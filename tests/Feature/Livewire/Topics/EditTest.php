<?php

declare(strict_types=1);

use App\Livewire\Topics\Edit;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Edit::class)
        ->assertStatus(200);
});
