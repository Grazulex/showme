<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Actions\Meals\CreateMealAction;
use App\Services\CalorieEstimationService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Throwable;

final class Upload extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $picture = null;

    public array $calorieData = [];

    public function render(): View
    {
        return view('livewire.dashboard.upload');
    }

    /**
     * @throws Throwable
     */
    public function updatedPicture(): void
    {
        $this->validate([
            'picture' => 'image|max:4096',
        ]);

        $path = $this->picture->store('uploads', [
            'disk' => 's3',
            'visibility' => 'public',
        ]);

        $url = Storage::disk('s3')->url($path);

        $result = app(CalorieEstimationService::class)
            ->estimateFromImage($url);

        if (! $result || ! $result['contains_food']) {
            Flux::toast(
                text: 'No food detected or estimation failed.',
                heading: 'Error',
                variant: 'danger',
            );

            return;
        }

        $this->calorieData = $result;

        $action = new CreateMealAction();
        $action->handle(
            auth()->user(),
            [
                'ingredients' => implode(', ', $result['items']),
                'calories' => $result['estimated_calories_kcal'],
                'created_at' => now(),
            ]
        );

        Flux::toast(
            text: 'Detected: '.implode(', ', $result['items']).' — ~'.$result['estimated_calories_kcal'].' kcal',
            heading: 'Calorie Estimate',
            variant: 'success',
        );
    }
}
