<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Actions\Topics\CreateTopicAction;
use App\Enums\UnitEnum;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

final class Create extends Component
{
    public string $name = '';

    public string $description = '';

    public string $unit = '';

    public bool $is_weight = false;

    public function render(): View
    {
        return view('livewire.topics.create',
            ['units' => UnitEnum::cases()]);
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
                'min:3',
                Rule::unique('topics', 'name')->where(
                    'user_id', Auth::user()->id
                ),
            ],
            'description' => 'required|string|max:255|min:3',
            'unit' => [
                'required',
                Rule::in(UnitEnum::cases()),
            ],
            'is_weight' => [
                Rule::unique('topics', 'is_weight')
                    ->where('user_id', Auth::user()->id),
            ],
        ]);

        $action = new CreateTopicAction();
        $action->handle(
            user: Auth::user(),
            attributes: [
                'name' => $this->name,
                'description' => $this->description,
                'unit' => $this->unit,
                'is_weight' => $this->is_weight,
            ]
        );

        $this->reset([
            'name',
            'description',
            'unit',
            'is_weight',
        ]);

        Flux::toast(
            text: 'Topic created successfully.',
            heading: 'Topics',
            variant: 'success'
        );

        Flux::modals()->close();

        $this->dispatch('reloadTopics');

    }
}
