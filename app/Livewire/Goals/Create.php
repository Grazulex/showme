<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use App\Actions\Goals\CreateGoalAction;
use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use DateTimeImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class Create extends Component
{
    public string $name;

    public int $topic_id;

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
            heading: 'Goals',
            text: 'Goal created successfully.',
            variant: 'success'
        );

        Flux::modals()->close();

        $this->dispatch('reloadGoals');
    }
}
