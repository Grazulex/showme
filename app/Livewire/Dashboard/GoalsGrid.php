<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use App\Models\Value;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class GoalsGrid extends Component
{
    public array $goals = [];

    public function mount(): void
    {
        $topics = Topic::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        foreach ($topics as $topic) {
            $goal = $topic->getFirstActiveGoal();
            if (! $goal) {
                continue;
            }

            $valueQuery = Value::query()
                ->where('topic_id', $topic->id)
                ->whereBetween('created_at', [$goal->started_at, $goal->ended_at]);

            $lower = (clone $valueQuery)->orderBy('value')->first()?->value;
            $higher = (clone $valueQuery)->orderByDesc('value')->first()?->value;
            $avg = (clone $valueQuery)->avg('value');
            $latest = (clone $valueQuery)->orderByDesc('created_at')->first()?->value;
            $count = (clone $valueQuery)->count();

            $progress = match ($goal->type) {
                GoalTypeEnum::increase => $latest > 0
                    ? min(100, ($latest / $goal->target) * 100)
                    : 0,

                GoalTypeEnum::decrease => $latest > 0
                    ? min(100, ($goal->target / $latest) * 100)
                    : 0,

                GoalTypeEnum::maintain => $latest > 0
                    ? 100 - min(100, abs(($latest - $goal->target) / $goal->target) * 100)
                    : 0,
            };

            $status = match (true) {
                $progress >= 90 => 'on_track',
                $progress >= 60 => 'warning',
                default => 'off_track',
            };

            $daysLeft = number_format(now()->diffInDays($goal->ended_at, false), 0);
            $daysTotal = Carbon::parse($goal->started_at)->diffInDays(Carbon::parse($goal->ended_at)) + 1;
            $recordFrequency = $count / max(Carbon::parse($goal->started_at)->diffInDays(now()) + 1, 1);

            $this->goals[] = [
                'id' => $topic->id,
                'name' => $topic->name,
                'unit' => $topic->unit->value,
                'goal_type' => $goal->type->label(),
                'goal_target' => $goal->target,
                'lower_value' => $lower,
                'higher_value' => $higher,
                'avg_value' => $avg,
                'latest_value' => $latest,
                'progress_percent' => round($progress, 1),
                'status' => $status,
                'days_left' => $daysLeft,
                'days_total' => $daysTotal,
                'goal_end' => Carbon::parse($goal->ended_at)->toDateString(),
                'values_count' => $count,
                'record_frequency' => $recordFrequency,
            ];
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.goals-grid');
    }
}
