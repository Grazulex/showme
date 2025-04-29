<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        $topics = Auth::user()->topics()
            ->latest()
            ->paginate(10);

        return view('livewire.topics.index', ['topics' => $topics]);
    }
}
