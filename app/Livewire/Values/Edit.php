<?php

declare(strict_types=1);

namespace App\Livewire\Values;

use App\Actions\Values\UpdateValueAction;
use App\Models\Topic;
use App\Models\Value;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

final class Edit extends Component
{
    public int $valueId;

    public int $topic_id;

    public float $value;

    public ?CarbonInterface $created_at;

    #[On('editValue')]
    public function edit(int $valueId): void
    {
        $this->valueId = $valueId;
        $value = $this->getValue();
        $this->topic_id = $value->topic_id;
        $this->value = (float) $value->value;
        $this->created_at = Carbon::parse($value->created_at);

        Flux::modal('edit-value')->show();
    }

    public function render(): View
    {
        return view('livewire.values.edit',
            [
                'topics' => Topic::query()
                    ->where('user_id', Auth::user()->id)
                    ->orderBy('name')
                    ->get(),
            ]);
    }

    public function submit(): void
    {
        $this->validate([
            'topic_id' => 'required|exists:topics,id',
            'value' => 'required|numeric',
            'created_at' => 'required|date',
        ]);

        $action = new UpdateValueAction();
        $action->handle(
            $this->getValue(),
            [
                'topic_id' => $this->topic_id,
                'value' => $this->value,
                'created_at' => $this->created_at,
            ]
        );

        $this->reset();

        Flux::toast(
            heading: 'Value updated',
            text: 'The value has been updated',
            variant: 'success'
        );

        Flux::modals()->close();

        $this->dispatch('reloadValues');

    }

    public function getValue(): Value
    {
        return Value::findOrFail($this->valueId);
    }
}
