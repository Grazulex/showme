<?php

use App\Livewire\Goals\Edit;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Edit::class)
        ->assertStatus(200);
});
