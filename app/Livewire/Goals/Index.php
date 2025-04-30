<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Index extends Component
{
    public function render(): View
    {
        return view('livewire.goals.index', ['goals' => $this->getGoals()]);
    }

    private function getGoals(): LengthAwarePaginator
    {
        return Auth::user()->goals()
            ->latest()
            ->paginate(10);
    }
}
