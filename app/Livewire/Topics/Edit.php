<?php

declare(strict_types=1);

namespace App\Livewire\Topics;

use App\Actions\Topics\UpdateTopicAction;
use App\Enums\UnitEnum;
use App\Models\Topic;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class Edit extends Component
{
    public int $topicId;

    public string $name = '';

    public string $description = '';

    public string $unit = '';

    public function render(): View
    {
        return view('livewire.topics.edit', ['units' => UnitEnum::cases()]);
    }

    #[On('editTopic')]
    public function edit(int $topicId): void
    {
        $this->topicId = $topicId;
        $topic = $this->getTopic();
        $this->name = $topic->name;
        $this->description = $topic->description;
        $this->unit = $topic->unit->value;

        Flux::modal('edit-topic')->show();
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
                )->ignore($this->topicId),
            ],
            'description' => 'required|string|max:255|min:3',
            'unit' => [
                'required',
                Rule::in(UnitEnum::cases()),
            ],
        ]);

        $action = new UpdateTopicAction();
        $action->handle(
            $this->getTopic(),
            [
                'name' => $this->name,
                'description' => $this->description,
                'unit' => $this->unit,
            ]
        );

        $this->reset(
            [
                'name',
                'description',
                'unit',
            ]
        );

        Flux::toast(
            text: 'Topic updated successfully',
            heading: 'topics',
            variant: 'success',
        );

        Flux::modal('edit-topic')->close();

        $this->dispatch('reloadTopics');

    }

    private function getTopic(): Topic
    {
        return Topic::findOrFail($this->topicId);
    }
}
