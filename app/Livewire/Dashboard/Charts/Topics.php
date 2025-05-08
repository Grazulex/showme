<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Charts;

use App\Models\Goal;
use App\Models\Topic;
use App\Models\Value;
use App\Services\Math;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

final class Topics extends Component
{
    public ?Topic $topic;

    public ?Goal $goal;

    public array $data = [];

    public function mount(int $topic_id): void
    {
        $this->topic = Topic::find($topic_id);
        if (! $this->topic instanceof Topic) {
            return;
        }

        /** @var Collection<Value> $values */
        $values = Value::where('topic_id', $this->topic->id)
            ->where('created_at', '>=', now()->subDays(90))
            ->orderBy('created_at')
            ->get();

        foreach ($values as $value) {
            $this->data[] = [
                'date' => $value->created_at->format('Y-m-d'),
                'value' => $value->value,
            ];
        }

        $this->goal = $this->topic->getFirstActifGoal();
    }

    public function render(Math $math): View
    {
        $slope = $math->linearTrend(array_column($this->data, 'value'));

        return view('livewire.dashboard.charts.topics',
            [
                'slope' => $slope,
            ]
        );
    }
}
