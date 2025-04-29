<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use Flux\Flux;
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

    public function delete(int $topicId): void
    {
        $topic = Auth::user()->topics()->findOrFail($topicId);
        $topic->delete();
        Flux::toast(heading : 'Topics', text: 'Topic deleted successfully.', variant: 'success');
    }
}
