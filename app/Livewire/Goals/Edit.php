<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use App\Actions\Goals\UpdateGoalAction;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

final class Edit extends Component
{
    public int $goalId;

    public string $name = '';

    public int $topic_id = 0;

    public string $type = GoalTypeEnum::decrease->value;

    public float $target = 0.0;

    public ?CarbonInterface $started_at;

    public ?CarbonInterface $ended_at;

    #[On('editGoal')]
    public function edit(int $goalId): void
    {
        $this->goalId = $goalId;
        $goal = $this->getGoal();
        $this->name = $goal->name;
        $this->topic_id = $goal->topic_id;
        $this->type = $goal->type->value;
        $this->target = (float) $goal->target;
        $this->started_at = Carbon::parse($goal->started_at);
        $this->ended_at = Carbon::parse($goal->ended_at);

        Flux::modal('edit-goal')->show();
    }

    public function getGoal(): Goal
    {
        return Goal::findOrFail($this->goalId);
    }

    public function render(): View
    {
        return view('livewire.goals.edit',
            [
                'types' => GoalTypeEnum::cases(),
                'topics' => Topic::query()
                    ->where('user_id', Auth::user()->id)
                    ->orderBy('name')
                    ->get(),
            ]
        );
    }

    public function submit(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'topic_id' => [
                'required',
                'integer',
                'exists:topics,id',
            ],
            'type' => [
                'required',
                Rule::in(GoalTypeEnum::cases()),
            ],
            'target' => [
                'required',
                'numeric',
                'min:0',
            ],
            'started_at' => [
                'required',
                'date',
            ],
            'ended_at' => [
                'required',
                'date',
                'after:started_at',
            ],
        ]);

        $action = new UpdateGoalAction();
        $action->handle(
            $this->getGoal(),
            [
                'name' => $this->name,
                'topic_id' => $this->topic_id,
                'type' => GoalTypeEnum::from($this->type),
                'target' => $this->target,
                'started_at' => $this->started_at,
                'ended_at' => $this->ended_at,
            ]
        );

        $this->reset();

        Flux::toast(
            text: 'The goal has been updated successfully.',
            heading: 'Goal updated',
            variant: 'success',
        );

        Flux::modals()->close();

        $this->dispatch('reloadGoals');
    }
}
