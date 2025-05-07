<?php

declare(strict_types=1);

namespace App\Livewire\Values;

use App\Actions\Values\CreateValueAction;
use App\Models\Topic;
use Carbon\CarbonInterface;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

final class Create extends Component
{
    public $topic_id = ''; // @pest-ignore-type

    public float $value;

    public ?CarbonInterface $created_at;

    public function mount(): void
    {
        $this->created_at = now();
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

    /**
     * @throws Throwable
     */
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

        $this->reset(['topic_id', 'value']);

        Flux::toast(
            text: 'Value created successfully.',
            heading: 'Values',
            variant: 'success',
        );

        Flux::modals()->close();

        $this->dispatch('reloadValues');
    }
}
