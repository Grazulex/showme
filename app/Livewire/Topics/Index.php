<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

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

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    #[On('reloadTopics')]
    public function reloadTopics(): void
    {
        $this->getTopics();
    }

    public function render(): View
    {
        $topics = $this->getTopics();

        return view('livewire.topics.index', ['topics' => $topics]);
    }

    public function delete(int $topicId): void
    {
        $topic = Auth::user()->topics()->findOrFail($topicId);
        $topic->delete();
        Flux::toast(
            text: 'Topic deleted successfully.',
            heading: 'Topics',
            variant: 'success'
        );
    }

    public function edit(int $topicId): void
    {
        $this->dispatch('editTopic', $topicId);
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

    private function getTopics(): LengthAwarePaginator
    {
        return Auth::user()->topics()
            ->tap(fn (Builder $query) => $this->sortBy !== '' && $this->sortBy !== '0' ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }
}
