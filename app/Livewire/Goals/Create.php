<?php

declare(strict_types=1);

namespace App\Livewire\Goals;

use App\Enums\GoalTypeEnum;
use App\Models\Topic;
use DateTimeImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Create extends Component
{
    public string $name = '';

    public int $topic_id = 0;

    public string $type;

    public float $target = 0;

    public DateTimeImmutable $target_date;

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

    public function submit(): void {}
}
