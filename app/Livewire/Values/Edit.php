<?php

declare(strict_types=1);

namespace App\Livewire\Values;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Edit extends Component
{
    public function render(): View
    {
        return view('livewire.values.edit');
    }
}
