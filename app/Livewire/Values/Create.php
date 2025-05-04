<?php

declare(strict_types=1);

namespace App\Livewire\Values;

use App\Actions\Values\CreateValueAction;
use App\Models\Topic;
use DateTimeImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Create extends Component
{
    public int $topic_id;

    public float $value;

    public DateTimeImmutable $created_at;

    public function mount(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function render(): View
    {
        return view('livewire.values.create',
            [
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
            'topic_id' => 'required|exists:topics,id',
            'value' => 'required|numeric',
            'created_at' => 'required|date',
        ]);

        $action = new CreateValueAction();
        $action->handle(
            Auth::user(),
            [
                'topic_id' => $this->topic_id,
                'value' => $this->value,
                'created_at' => $this->created_at,
            ]
        );

        $this->reset();

        Flux::toast(
            heading: 'Values',
            text: 'Value created successfully.',
            variant: 'success',
        );

        Flux::modals()->close();

        $this->dispatch('reloadValues');
    }
}
