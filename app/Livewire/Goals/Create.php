<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use App\Actions\Goals\CreateGoalAction;
use App\Enums\GoalTypeEnum;
use App\Models\Goal;
use App\Models\Topic;
use DateTimeImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

final class Create extends Component
{
    public string $name;

    public $topic_id = ''; // @pest-ignore-type

    public string $type;

    public float $target;

    public ?DateTimeImmutable $started_at = null;

    public ?DateTimeImmutable $ended_at = null;

    public function render(): View
    {
        return view('livewire.goals.create',
            [
                'types' => GoalTypeEnum::cases(),
                'topics' => Topic::query()
                    ->where('user_id', Auth::user()->id)
                    ->orderBy('name')
                    ->get(),
            ]
        );
    }

    /**
     * @throws Throwable
     */
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
                'after_or_equal:today',
            ],
            'ended_at' => [
                'date',
                'after:started_at',
            ],
        ]);

        $overlappingGoal = Goal::query()
            ->where('user_id', Auth::id())
            ->where('topic_id', $this->topic_id)
            ->where(function ($query) {
                $query->whereBetween('started_at', [$this->started_at, $this->ended_at])
                    ->orWhereBetween('ended_at', [$this->started_at, $this->ended_at])
                    ->orWhere(function ($query) {
                        $query->where('started_at', '<=', $this->started_at)
                            ->where('ended_at', '>=', $this->ended_at);
                    });
            })
            ->exists();

        if ($overlappingGoal) {
            throw ValidationException::withMessages([
                'started_at' => 'A goal already exists for this topic and user within the specified date range.',
                'ended_at' => 'A goal already exists for this topic and user within the specified date range.',
            ]);
        }

        $action = new CreateGoalAction();
        $action->handle(
            Auth::user(),
            [
                'name' => $this->name,
                'topic_id' => $this->topic_id,
                'type' => $this->type,
                'target' => $this->target,
                'started_at' => $this->started_at,
                'ended_at' => $this->ended_at,
            ]
        );

        $this->reset();

        Flux::toast(
            text: 'Goal created successfully.',
            heading: 'Goals',
            variant: 'success'
        );

        Flux::modals()->close();

        $this->dispatch('reloadGoals');
    }
}
