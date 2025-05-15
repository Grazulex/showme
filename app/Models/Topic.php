<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UnitEnum;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Database\Factories\TopicFactory;
use DateTimeImmutable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property UnitEnum $unit
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property-read User $user
 * @property-read Collection<int, Goal> $goals
 * @property-read int|null $goals_count
 * @property-read Collection<int, Value> $values
 * @property-read int|null $values_count
 * @property string $slug
 * @property string $description
 *
 * @method static TopicFactory factory($count = null, $state = [])
 * @method static Builder<static>|Topic newModelQuery()
 * @method static Builder<static>|Topic newQuery()
 * @method static Builder<static>|Topic query()
 * @method static Builder<static>|Topic whereCreatedAt($value)
 * @method static Builder<static>|Topic whereDescription($value)
 * @method static Builder<static>|Topic whereId($value)
 * @method static Builder<static>|Topic whereName($value)
 * @method static Builder<static>|Topic whereSlug($value)
 * @method static Builder<static>|Topic whereUnit($value)
 * @method static Builder<static>|Topic whereUpdatedAt($value)
 * @method static Builder<static>|Topic whereUserId($value)
 *
 * @mixin Eloquent
 */
final class Topic extends Model
{
    /** @use HasFactory<TopicFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'user_id',
        'unit',
    ];

    protected $casts = [
        'unit' => UnitEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'topic_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'topic_id');
    }

    public function getLastValueBeforeDate(DateTimeImmutable|Carbon $date): ?Value
    {
        return Value::query()
            ->where('topic_id', $this->id)
            ->where('created_at', '<', $date)
            ->orderByDesc('created_at')
            ->first();
    }

    public function getFirstValueAfterDate(DateTimeImmutable|Carbon $date): ?Value
    {
        return Value::query()
            ->where('topic_id', $this->id)
            ->where('created_at', '>', $date)
            ->orderBy('created_at')
            ->first();
    }

    public function getFirstActiveGoal(): ?Goal
    {
        return Goal::query()
            ->where('topic_id', $this->id)
            ->where('started_at', '<=', now())
            ->where(function (Builder $query): void {
                $query->where('ended_at', '>=', now())
                    ->orWhereNull('ended_at');
            })
            ->orderBy('started_at')
            ->first();
    }
}
