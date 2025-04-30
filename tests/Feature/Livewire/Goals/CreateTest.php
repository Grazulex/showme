<?php

use App\Livewire\Goals\Create;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Create::class)
        ->assertStatus(200);
});
