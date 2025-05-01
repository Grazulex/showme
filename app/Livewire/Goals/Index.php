<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public string $sortBy = 'ended_at';

    public string $sortDirection = 'asc';

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

    public function edit(int $goalId): void
    {
        $this->dispatch('editGoal', $goalId);
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    private function getGoals(): LengthAwarePaginator
    {
        return Auth::user()->goals()
            ->tap(fn (Builder $query) => $this->sortBy !== '' && $this->sortBy !== '0' ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }
}
