<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Enums\GoalTypeEnum;
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
        $avg = $math->averageRelativeChange($this->data);
        if ($this->activeGoal instanceof Goal && $this->activeGoal->type === GoalTypeEnum::decrease) {
            $slope = -$slope;
        }

        if (! is_null($avg)) {
            $label = ($avg > 0 ? '+' : '').number_format($avg, 2);
        } else {
            $label = null;
        }

        return view('livewire.topics.sparkline-values', ['slope' => $slope, 'label' => $label]);
    }
}
