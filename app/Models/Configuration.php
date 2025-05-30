<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Topic|null $topicCalorieIn
 * @property-read Topic|null $topicCalorieOut
 * @property-read Topic|null $topicWeight
 * @property-read User|null $user
 *
 * @method static \Database\Factories\ConfigurationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration query()
 *
 * @property int $id
 * @property int $topic_weight
 * @property int $topic_calorie_in
 * @property int $topic_calorie_out
 * @property int $user_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration whereTopicCalorieIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration whereTopicCalorieOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration whereTopicWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Configuration whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class Configuration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'topic_weight',
        'topic_calorie_in',
        'topic_calorie_out',
        'user_id',
    ];

    public function topicWeight(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_weight');
    }

    public function topicCalorieIn(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_calorie_in');
    }

    public function topicCalorieOut(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_calorie_out');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
