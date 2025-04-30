<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GoalTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property int $topic_id
 * @property int $user_id
 * @property GoalTypeEnum $type
 * @property string $target
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Topic $topic
 * @property-read User $user
 *
 * @method static \Database\Factories\GoalFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goal whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class Goal extends Model
{
    /** @use HasFactory<\Database\Factories\GoalFactory> */
    use HasFactory;

    protected $casts = [
        'type' => GoalTypeEnum::class,
        'ended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
