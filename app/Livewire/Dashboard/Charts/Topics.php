<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Charts;

use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Topics extends Component
{
    public ?Topic $topic;

    public array $data = [];

    public function mount(int $topic_id): void
    {
        $this->topic = Topic::find($topic_id);
        if (! $this->topic) {
            return;
        }
        $values = $this->topic->values()
            ->where('created_at', '>=', now()->subDays(90))
            ->orderBy('created_at')
            ->select(['created_at', 'value'])
            ->get();
        $this->data = $values->map(function ($value) {
            return [
                'date' => $value->created_at->format('Y-m-d'),
                'value' => $value->value,
            ];
        })->toArray();
    }

    public function render(): View
    {
        return view('livewire.dashboard.charts.topics');
    }
}
