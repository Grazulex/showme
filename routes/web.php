<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Livewire\Goals\Index as GoalsIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Topics\Index as TopicsIndex;
use App\Livewire\Values\Index as ValuesIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/', DashboardController::class)
        ->name('home');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('topics', TopicsIndex::class)->name('topics.index');
    Route::get('goals', GoalsIndex::class)->name('goals.index');
    Route::get('values', ValuesIndex::class)->name('values.index');
});

require __DIR__.'/auth.php';
