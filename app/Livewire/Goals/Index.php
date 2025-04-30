<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    #[On('reloadGoals')]
    public function reloadGoals(): void
    {
        $this->getGoals();
    }

    public function delete(int $goalId): void
    {
        $goal = Auth::user()->goals()->findOrFail($goalId);
        $goal->delete();
        Flux::toast(
            heading: 'Goals',
            text: 'Goal deleted successfully.',
            variant: 'success'
        );
    }

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
