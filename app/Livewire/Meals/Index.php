<?php

declare(strict_types=1);

namespace App\Livewire\Meals;

use Flux\DateRange;
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

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public int $filterTopicId = 0;

    public ?DateRange $filterDateRange;

    #[On('reloadMeals')]
    public function reloadMeals(): void
    {
        $this->resetPage();
    }

    public function delete(int $valueId): void
    {
        $value = Auth::user()->meals()->findOrFail($valueId);
        $value->delete();

        Flux::toast(
            text: 'Meal deleted successfully.',
            heading: 'Meals',
            variant: 'success'
        );
    }

    public function mount(): void
    {
        $this->filterDateRange = DateRange::thisMonth();
    }

    public function render(): View
    {
        $meals = $this->getMeals();
        $topics = Auth::user()->topics()
            ->orderBy('name')
            ->get();

        return view('livewire.meals.index', ['meals' => $meals, 'topics' => $topics]);
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

    public function edit(int $mealId): void
    {
        $this->dispatch('editMeal', $mealId);
    }

    public function copy(int $mealId): void
    {
        $meal = Auth::user()->meals()->findOrFail($mealId);
        $meal->replicate(
            ['id', 'created_at', 'updated_at']
        )->fill([
            'created_at' => now(),
            'updated_at' => now(),
        ])->save();

        Flux::toast(
            text: 'Meal copied successfully.',
            heading: 'Meals',
            variant: 'success'
        );
    }

    private function getMeals(): LengthAwarePaginator
    {
        return Auth::user()->meals()
            ->tap(fn (Builder $query) => $this->sortBy !== '' && $this->sortBy !== '0' ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->filterTopicId !== 0 ? $query->where('topic_id', $this->filterTopicId) : $query)
            ->tap(fn (Builder $query) => $this->filterDateRange instanceof DateRange ? $query->whereBetween('created_at', [$this->filterDateRange->start, $this->filterDateRange->end]) : $query)
            ->paginate(10);
    }
}
