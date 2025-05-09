<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Services\CalorieEstimationService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

final class Upload extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $picture = null;

    public float $calorieEstimate;

    public function render(): View
    {
        return view('livewire.dashboard.upload');
    }

    public function updatedPicture(): void
    {
        $this->validate([
            'picture' => 'image|max:4096',
        ]);

        $path = $this->picture->store('uploads', 'public');
        $url = asset("storage/{$path}");

        $this->calorieEstimate = app(CalorieEstimationService::class)
            ->estimateFromImage($url);

        if ($this->calorieEstimate > 0) {
            Flux::toast(
                text: $this->calorieEstimate,
                heading: 'Calorie Estimate',
                variant: 'success',
            );
        } else {
            Flux::toast(
                text: 'Estimation calorie failed',
                heading: 'Calorie Estimation Failed',
                variant: 'danger',
            );
        }
    }
}
