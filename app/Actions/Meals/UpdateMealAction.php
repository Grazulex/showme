<?php

declare(strict_types=1);

namespace App\Actions\Meals;

use App\Models\Meal;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateMealAction
{
    /**
     * @throws Throwable
     */
    public function handle(Meal $meal, array $attributes): Meal
    {
        return DB::transaction(function () use ($attributes, $meal): Meal {
            $meal->update(
                [
                    'ingredients' => $attributes['ingredients'],
                    'calories' => $attributes['calories'],
                    'created_at' => $attributes['created_at'],
                ]
            );
            $meal->refresh();

            return $meal;
        });
    }
}
