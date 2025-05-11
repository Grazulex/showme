<?php

declare(strict_types=1);

namespace App\Actions\Meals;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateMealAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, array $attributes): Meal
    {
        return DB::transaction(function () use ($attributes, $user) {
            return Meal::create(
                [
                    'user_id' => $user->id,
                    'topic_id' => $attributes['topic_id'],
                    'ingredients' => $attributes['ingredients'],
                    'calories' => $attributes['calories'],
                    'created_at' => $attributes['created_at'],
                ]
            );
        });
    }
}
