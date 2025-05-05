<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class AvgValues extends Component
{
    public array $data;

    public Topic $topic;

    public ?Goal $activeGoal = null;

    public function mount(Topic $topic): void
    {
        $this->data = Value::where('topic_id', $topic->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'asc')
            ->pluck('value')
            ->toArray();

        $this->activeGoal = Topic::find($topic->id)->getFirstActifGoal();

    }

    public function render(): View
    {
        $slope = $this->linearTrend($this->data);
        $avg = $this->averageRelativeChange($this->data);
        if ($this->activeGoal instanceof Goal && $this->activeGoal->type === GoalTypeEnum::decrease) {
            $slope = -$slope;
        }

        if (! is_null($avg)) {
            $label = ($avg > 0 ? '+' : '').number_format($avg, 2);
        } else {
            $label = null;
        }

        return view('livewire.topics.avg-values', ['slope' => $slope, 'label' => $label]);
    }

    public function linearTrend(array $data): float
    {
        $n = count($data);
        if ($n < 2) {
            return 0;
        }

        $x = range(1, $n);
        $y = $data;

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(fn (float|int $xi, float|int $yi): float|int => $xi * $yi, $x, $y));
        $sumX2 = array_sum(array_map(fn (float|int $xi): int => $xi * $xi, $x));

        return ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
    }

    public function averageRelativeChange(array $data): ?float
    {
        $n = count($data);
        if ($n < 2) {
            return null;
        }

        $totalChange = 0;
        $count = 0;

        for ($i = 0; $i < $n - 1; $i++) {
            $current = $data[$i];
            $next = $data[$i + 1];

            if ($current !== 0) {
                $change = (($next - $current) / $current) * 100;
                $totalChange += $change;
                $count++;
            }
        }

        return $count > 0 ? $totalChange / $count : null;
    }
}
