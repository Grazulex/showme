<?php

declare(strict_types=1);

namespace App\Livewire\Values;

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

    public string $sortDirection = 'asc';

    public int $filterTopicId = 0;

    public ?DateRange $filterDateRange;

    #[On('reloadValues')]
    public function reloadValues(): void
    {
        $this->resetPage();
    }

    public function delete(int $valueId): void
    {
        $value = Auth::user()->values()->findOrFail($valueId);
        $value->delete();

        // TODO : update diff & color next entry

        Flux::toast(
            heading: 'Values',
            text: 'Value deleted successfully.',
            variant: 'success'
        );
    }

    public function mount()
    {
        $this->filterDateRange = DateRange::thisMonth();
    }

    public function render(): View
    {
        $values = $this->getValues();
        $topics = Auth::user()->topics()
            ->orderBy('name')
            ->get();

        return view('livewire.values.index', ['values' => $values, 'topics' => $topics]);
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

    public function edit(int $valueId): void
    {
        $this->dispatch('editValue', $valueId);
    }

    private function getValues(): LengthAwarePaginator
    {
        return Auth::user()->values()
            ->tap(fn (Builder $query) => $this->sortBy !== '' && $this->sortBy !== '0' ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->tap(fn (Builder $query) => $this->filterTopicId !== 0 ? $query->where('topic_id', $this->filterTopicId) : $query)
            ->tap(fn (Builder $query) => $this->filterDateRange ? $query->whereBetween('created_at', [$this->filterDateRange->start, $this->filterDateRange->end]) : $query)
            ->paginate(10);
    }
}
