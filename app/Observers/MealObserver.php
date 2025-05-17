<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Configuration;
use App\Models\Meal;
use App\Models\Topic;
use App\Models\Value;
use Illuminate\Support\Facades\Auth;

final class MealObserver
{
    public function created(Meal $meal): void
    {
        $this->updateCalorieIn($meal);
    }

    public function updated(Meal $meal): void
    {
        $this->updateCalorieIn($meal);
    }

    public function deleted(Meal $meal): void
    {
        $this->updateCalorieIn($meal);
    }

    private function updateCalorieIn(Meal $meal): void
    {
        if ($calorieIn = $this->getIfTopicCalorieOut()) {
            $TotalCaloriesSameDay = Meal::where('user_id', Auth::id())
                ->whereDate('created_at', $meal->created_at->toDateString())
                ->sum('calories');

            $value = Value::where('topic_id', $calorieIn->id)
                ->where('user_id', Auth::id())
                ->whereDate('created_at', $meal->created_at->toDateString())
                ->first();

            if ($value) {
                $value->update([
                    'value' => $TotalCaloriesSameDay,
                ]);
            } else {
                Value::create([
                    'topic_id' => $calorieIn->id,
                    'user_id' => Auth::id(),
                    'value' => $TotalCaloriesSameDay,
                    'created_at' => $meal->created_at->toDateString(),
                ]);
            }
        }
    }

    private function getIfTopicCalorieOut(): ?Topic
    {
        $configuration = Configuration::where('user_id', Auth::id())->first()->topic_calorie_in;
        if ($configuration) {
            return Topic::find($configuration);
        }

        return null;
    }
}
