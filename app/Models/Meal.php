<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property string $ingredients
 * @property int $calories
 * @property int $user_id
 * @property-read User $user
 * @method static TopicFactory factory($count = null, $state = [])
 * @method static Builder<static>|Meal newModelQuery()
 * @method static Builder<static>|Meal newQuery()
 * @method static Builder<static>|Meal query()
 * @method static Builder<static>|Meal whereCreatedAt($value)
 * @method static Builder<static>|Meal whereId($value)
 * @method static Builder<static>|Meal whereIngredients($value)
 * @method static Builder<static>|Meal whereCalories($value)
 * @method static Builder<static>|Meal whereUserId($value)
 * @method static Builder<static>|Meal whereUpdatedAt($value)
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @mixin \Eloquent
 */
final class Meal extends Model
{
    /** @use HasFactory<TopicFactory> */
    use HasFactory;

    protected $fillable = [
        'ingredients',
        'calories',
        'user_id',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalForToday(): int
    {
        return $this->whereDate('created_at', now()->format('Y-m-d'))
            ->sum('calories');
    }
}
