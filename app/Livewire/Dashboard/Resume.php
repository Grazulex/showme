<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Topic;
use App\Models\Value;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Resume extends Component
{
    public array $resumes = [];

    public function mount(): void
    {
        $topics = Topic::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get();
        foreach ($topics as $topic) {
            $this->resumes[] = [
                'id' => $topic->id,
                'name' => $topic->name,
                'unit' => $topic->unit->value,
                'goal_type' => $topic->getFirstActiveGoal()->type->label(),
                'goal_target' => $topic->getFirstActiveGoal()->target,
                'lower_value_in_goal_range' => Value::query()
                    ->where('topic_id', $topic->id)
                    ->where('created_at', '>=', $topic->getFirstActiveGoal()->started_at)
                    ->where('created_at', '<=', $topic->getFirstActiveGoal()->ended_at)
                    ->orderBy('value')
                    ->first()?->value,
                'higher_value_in_goal_range' => Value::query()
                    ->where('topic_id', $topic->id)
                    ->where('created_at', '>=', $topic->getFirstActiveGoal()->started_at)
                    ->where('created_at', '<=', $topic->getFirstActiveGoal()->ended_at)
                    ->orderBy('value', 'desc')
                    ->first()?->value,
                'avg_value_in_goal_range' => Value::query()
                    ->where('topic_id', $topic->id)
                    ->where('created_at', '>=', $topic->getFirstActiveGoal()->started_at)
                    ->where('created_at', '<=', $topic->getFirstActiveGoal()->ended_at)
                    ->avg('value'),
            ];
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.resume');
    }
}
