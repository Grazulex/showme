<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Models\Topic;
use App\Models\Value;
use Livewire\Component;

final class SparlineValues extends Component
{
    public array $data;

    public Topic $topic;

    public function mount(Topic $topic): void
    {
        $this->data = Value::where('topic_id', $topic->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->pluck('value')
            ->toArray();

    }

    public function render()
    {
        return view('livewire.topics.sparline-values');
    }
}
