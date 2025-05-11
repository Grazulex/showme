<?php

declare(strict_types=1);

namespace App\Livewire\Meals;

use App\Actions\Meals\UpdateMealAction;
use App\Models\Meal;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class Edit extends Component
{
    public int $mealId;

    public string $ingredients;

    public float $calories;

    public ?CarbonInterface $created_at;

    #[On('editMeal')]
    public function edit(int $mealId): void
    {
        $this->mealId = $mealId;
        $meal = $this->getMeal();
        $this->ingredients = $meal->ingredients;
        $this->calories = (float) $meal->calories;
        $this->created_at = Carbon::parse($meal->created_at);

        Flux::modal('edit-meal')->show();
    }

    public function render(): View
    {
        return view('livewire.meals.edit');
    }

    /**
     * @throws Throwable
     */
    public function submit(): void
    {
        $this->validate([
            'ingredients' => 'required|string',
            'calories' => 'required|numeric',
            'created_at' => 'required|date',
        ]);

        $action = new UpdateMealAction();
        $action->handle(
            $this->getMeal(),
            [
                'ingredients' => $this->ingredients,
                'calories' => $this->calories,
                'created_at' => $this->created_at,
            ]
        );

        $this->reset();

        Flux::toast(
            text: 'The meal has been updated',
            heading: 'Meal updated',
            variant: 'success'
        );

        Flux::modals()->close();

        $this->dispatch('reloadMeals');

    }

    public function getMeal(): Meal
    {
        return Meal::findOrFail($this->mealId);
    }
}
