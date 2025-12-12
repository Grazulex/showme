<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Configuration;
use App\Models\Topic;
use App\Models\Value;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class HeroStats extends Component
{
    public int $caloriesToday = 0;

    public int $caloriesTarget = 0;

    public ?float $currentWeight = null;

    public ?float $weightChange = null;

    public ?float $weeklyProgress = null;

    public function mount(): void
    {
        $user = Auth::user();

        // Calories Today
        $config = Configuration::where('user_id', $user->id)->first();

        if ($config && $config->topic_calorie_in) {
            $caloriesIn = Value::query()
                ->where('topic_id', $config->topic_calorie_in)
                ->whereDate('created_at', today())
                ->sum('value');

            $this->caloriesToday = (int) $caloriesIn;
        }

        $this->caloriesTarget = $user->calories_each_day ?? 2400;

        // Current Weight
        if ($config && $config->topic_weight) {
            $weightTopic = Topic::find($config->topic_weight);

            if ($weightTopic) {
                $latestWeight = Value::query()
                    ->where('topic_id', $weightTopic->id)
                    ->where('user_id', $user->id)
                    ->orderByDesc('created_at')
                    ->first();

                if ($latestWeight) {
                    $this->currentWeight = (float) $latestWeight->value;

                    // Get weight from 7 days ago
                    $weekAgo = Value::query()
                        ->where('topic_id', $weightTopic->id)
                        ->where('user_id', $user->id)
                        ->where('created_at', '<=', now()->subDays(7))
                        ->orderByDesc('created_at')
                        ->first();

                    if ($weekAgo) {
                        $this->weightChange = $this->currentWeight - (float) $weekAgo->value;
                        $this->weeklyProgress = ($this->weightChange / (float) $weekAgo->value) * 100;
                    }
                }
            }
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.hero-stats');
    }
}
