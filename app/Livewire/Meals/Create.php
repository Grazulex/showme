<?php

declare(strict_types=1);

namespace App\Livewire\Meals;

use App\Actions\Meals\CreateMealAction;
use Carbon\CarbonInterface;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

final class Create extends Component
{
    public string $ingredients = '';

    public float $calories = 0;

    public ?CarbonInterface $created_at;

    public function mount(): void
    {
        $this->created_at = now();
    }

    public function render(): View
    {
        return view('livewire.meals.create');
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

        $action = new CreateMealAction();
        $action->handle(
            Auth::user(),
            [
                'ingredients' => $this->ingredients,
                'calories' => $this->calories,
                'created_at' => $this->created_at,
            ]
        );

        $this->reset(['ingredients', 'calories']);

        Flux::toast(
            text: 'Meal created successfully.',
            heading: 'Meals',
            variant: 'success',
        );

        Flux::modals()->close();

        $this->dispatch('reloadMeals');
    }
}
