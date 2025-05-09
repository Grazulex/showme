<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Charts;

use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use App\Models\Value;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property Topic|null $topic
 * @property Goal|null $goal
 * @property Collection<int, Value> $values
 * @property array<int, array<string, string|float>> $chartData
 * @property float|null $trend
 * @property float|null $score
 * @property string|null $trendState
 * @property float|null $gap
 * @property float|null $projection
 * @property bool|null $willReachTarget
 * @property Carbon|null $estimatedTargetDate
 */
final class Topics extends Component
{
    public ?Topic $topic;

    public ?Goal $goal;

    public Collection $values;

    public array $chartData = [];

    public ?float $trend = null;

    public ?float $score = null;

    public ?string $trendState = null;

    public ?float $gap = null;

    public ?float $projection = null;

    public ?bool $willReachTarget = null;

    public ?Carbon $estimatedTargetDate = null;

    public function mount(int $topic_id): void
    {
        $this->topic = Topic::find($topic_id);
        if (! $this->topic instanceof Topic) {
            return;
        }
        $this->computeProgress();
    }

    public function computeProgress(): void
    {
        $this->goal = $this->topic->getFirstActiveGoal();
        $goal = $this->goal;
        /** @var Collection<Value> $values */
        $values = $this->topic->values()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', $goal->started_at)
            ->where('created_at', '<=', $goal->ended_at)
            ->orderBy('created_at')
            ->get();

        $this->values = $values;

        if ($values->count() < 2 || ! $goal instanceof Goal) {
            $this->chartData = [];
            $this->trend = null;
            $this->score = null;
            $this->trendState = 'neutral';

            return;
        }

        $this->chartData = $values->map(function (Value $v) use ($goal): array {
            return [
                'date' => $v->created_at->format('Y-m-d'),
                'value' => $v->value,
                'target' => $goal->target,
            ];
        })->toArray();

        $this->trend = $values
            ->map(fn (Value $item, int $i): ?float => $i > 0 ? $item->value - $values[$i - 1]->value : null)
            ->filter() // Collection<int|float>
            ->avg(); // float|null

        $latest = $values->last()->value;
        $target = $goal->target;

        $this->gap = $latest - $target;

        if ($goal->type === GoalTypeEnum::increase) {
            $this->score = $latest >= $target ? 100 : round(($latest / $target) * 100, 1);
            $this->trendState = $this->trend > 0 ? 'good' : 'bad';
        } elseif ($goal->type === GoalTypeEnum::decrease) {
            $this->score = $latest <= $target ? 100 : round(($target / $latest) * 100, 1);
            $this->trendState = $this->trend < 0 ? 'good' : 'bad';
        } else { // maintain
            $diff = abs($latest - $target);
            $this->score = max(0, 100 - $diff);
            $this->trendState = $diff < 1 ? 'good' : ($diff < 5 ? 'neutral' : 'bad');
        }

        $daysTotal = Carbon::parse($goal->started_at)->diffInDays($goal->ended_at);
        $daysSoFar = Carbon::parse($goal->started_at)->diffInDays(now());

        if ($daysSoFar > 0 && $this->trend !== null) {
            $estimatedValue = $values->first()->value + ($this->trend * $daysTotal);
            $this->projection = round($estimatedValue, 2);

            if ($goal->type === GoalTypeEnum::increase) {
                $this->willReachTarget = $this->projection >= $target;
            } elseif ($goal->type === GoalTypeEnum::decrease) {
                $this->willReachTarget = $this->projection <= $target;
            } else {
                $this->willReachTarget = abs($this->projection - $target) < 1.0;
            }
        } else {
            $this->projection = null;
            $this->willReachTarget = null;
        }

        if ($this->trend !== null) {
            $firstValue = $values->first()->value;
            $remaining = $goal->target - $firstValue;

            $daysNeeded = $remaining / $this->trend;

            if ($daysNeeded > 0 && is_finite($daysNeeded)) {
                $this->estimatedTargetDate = Carbon::parse($goal->started_at)->copy()->addDays((int) round($daysNeeded));
            } else {
                $this->estimatedTargetDate = null;
            }
        } else {
            $this->estimatedTargetDate = null;
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.charts.topics');
    }
}
