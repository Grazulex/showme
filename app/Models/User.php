<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\ActivityEnum;
use App\Services\CalorieEstimationService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property CarbonInterface|null $birth_at
 * @property int|null $height
 * @property int|null $calories_each_day
 * @property ActivityEnum $activity
 * @property string|null $gender
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property-read Collection<int, Topic> $topics
 * @property-read Collection<int, Goal> $goals
 * @property-read string $initials
 * @property CarbonInterface|null $email_verified_at
 * @property-read Collection<int, Value> $values
 * @property-read int|null $topics_count
 * @property-read int|null $goals_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $values_count
 * @property-read Collection<int, Meal> $meals
 * @property-read int|null $meals_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'birth_at',
        'height',
        'calories_each_day',
        'activity',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'user_id');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'user_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'user_id');
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class, 'user_id');
    }

    public function updateTDEE(): void
    {
        $weight = 80;
        $lastWeight = Topic::where('user_id', $this->id)
            ->where('is_weight', true)
            ->first();
        if ($lastWeight) {
            $weight = Value::where('user_id', $this->id)
                ->where('topic_id', $lastWeight->id)
                ->orderByDesc('created_at')
                ->first()
                ?->value;
        }
        $this->calories_each_day = (int) new CalorieEstimationService()->calculateTDEE(
            weight: (float) $weight,
            height: $this->height,
            age: (int) Carbon::parse($this->birth_at)->diffInYears(),
            gender: $this->gender,
            activity: (float) $this->activity->value
        );
        $this->save();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_at' => 'datetime',
            'activity' => ActivityEnum::class,
        ];
    }
}
