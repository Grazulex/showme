<?php

declare(strict_types=1);

namespace App\Livewire\Configurations;

use App\Models\Configuration;
use App\Models\Topic;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Index extends Component
{
    public $topicWeight; // @pest-ignore-type

    public $topicCalorieIn; // @pest-ignore-type

    public $topicCalorieOut; // @pest-ignore-type

    public function mount(): void
    {
        $configuration = Configuration::first();
        if ($configuration) {
            /*
             * @phpstan-ignore-next-line
             */
            $this->topicWeight = $configuration->topicWeight->id;
            /*
             * @phpstan-ignore-next-line
             */
            $this->topicCalorieIn = $configuration->topicCalorieIn->id;
            /*
             * @phpstan-ignore-next-line
             */
            $this->topicCalorieOut = $configuration->topicCalorieOut->id;
        }
    }

    public function render(): View
    {
        $topics = Topic::where('user_id', Auth::user()->id)
            ->get();

        return view('livewire.configurations.index',
            [
                'topics' => $topics,
            ]
        );
    }

    public function submit(): void
    {
        $this->validate([
            'topicWeight' => 'required',
            'topicCalorieIn' => 'required',
            'topicCalorieOut' => 'required',
        ]);

        $configuration = Configuration::first();
        if (! $configuration) {
            $configuration = new Configuration();
        }
        $configuration->topic_weight = Topic::where('id', $this->topicWeight)->first()->id;
        $configuration->topic_calorie_in = Topic::where('id', $this->topicCalorieIn)->first()->id;
        $configuration->topic_calorie_out = Topic::where('id', $this->topicCalorieOut)->first()->id;
        $configuration->save();

        Flux::toast('Configuration saved', variant: 'success');
    }
}
