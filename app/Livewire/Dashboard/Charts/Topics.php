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
        $this->goal = $this->topic->getFirstActifGoal();
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

        // Préparation des données pour le graphique
        $this->chartData = $values->map(function (Value $v) use ($goal): array {
            return [
                'date' => $v->created_at->format('Y-m-d'),
                'value' => $v->value,
                'target' => $goal->target,
            ];
        })->toArray();

        // Calcul de la tendance moyenne
        $this->trend = $values
            ->map(fn (Value $item, int $i): ?float => $i > 0 ? $item->value - $values[$i - 1]->value : null)
            ->filter() // Collection<int|float>
            ->avg(); // float|null

        $latest = $values->last()->value;
        $target = $goal->target;

        $this->gap = $latest - $target; // peut être positif, négatif ou 0

        // Calcul du score d’atteinte
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
    }

    public function render(): View
    {
        return view('livewire.dashboard.charts.topics');
    }
}
