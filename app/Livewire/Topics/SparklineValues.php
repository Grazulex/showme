<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Models\Goal;
use App\Models\Topic;
use App\Models\Value;
use App\Services\Math;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class SparklineValues extends Component
{
    public array $data;

    public Topic $topic;

    public ?Goal $activeGoal = null;

    public function mount(Topic $topic): void
    {
        $this->data = Value::where('topic_id', $topic->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at')
            ->pluck('value')
            ->toArray();

        $this->activeGoal = Topic::find($topic->id)->getFirstActifGoal();

    }

    public function render(Math $math): View
    {
        $slope = $math->linearTrend($this->data);

        return view('livewire.topics.sparkline-values', ['slope' => $slope]);
    }
}
