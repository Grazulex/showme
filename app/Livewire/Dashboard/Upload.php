<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

final class Upload extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $picture = null;

    public function render(): View
    {
        return view('livewire.dashboard.upload');
    }

    public function uploadPicture(): void
    {
        $this->validate([
            'picture' => 'image|max:4096', // 1MB Max
        ]);

        $this->picture->store('uploads', 'public');
    }
}
