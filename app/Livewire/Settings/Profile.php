<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Enums\ActivityEnum;
use App\Models\User;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public ?int $height;

    public ?string $activity;

    public ?string $gender = null;

    public ?DateTimeImmutable $birth_at;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->height = Auth::user()->height;
        $this->birth_at = Carbon::parse(Auth::user()->birth_at)->toImmutable();
        $this->activity = Auth::user()->activity->value;
        $this->gender = Auth::user()->gender;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'height' => [
                'required',
                'integer',
            ],
            'birth_at' => [
                'required',
                'date',
            ],
            'activity' => [
                'required',
                Rule::in(ActivityEnum::cases()),
            ],
            'gender' => [
                'nullable',
                'string',
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('birth_at') || $user->isDirty('height') || $user->isDirty('activity')) {
            $user->updateTDEE();
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $activities = ActivityEnum::cases();

        return view('livewire.settings.profile',
            [
                'activities' => $activities,
            ]);
    }
}
