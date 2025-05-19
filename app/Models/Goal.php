<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GoalTypeEnum;
use Carbon\CarbonInterface;
use Database\Factories\GoalFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $topic_id
 * @property int $user_id
 * @property GoalTypeEnum $type
 * @property float $target
 * @property CarbonInterface|null $started_at
 * @property CarbonInterface|null $ended_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property-read User $user
 * @property-read Topic $topic
 * @method static GoalFactory factory($count = null, $state = [])
 * @method static Builder<static>|Goal newModelQuery()
 * @method static Builder<static>|Goal newQuery()
 * @method static Builder<static>|Goal query()
 * @method static Builder<static>|Goal whereCreatedAt($value)
 * @method static Builder<static>|Goal whereEndedAt($value)
 * @method static Builder<static>|Goal whereId($value)
 * @method static Builder<static>|Goal whereName($value)
 * @method static Builder<static>|Goal whereStartedAt($value)
 * @method static Builder<static>|Goal whereTarget($value)
 * @method static Builder<static>|Goal whereTopicId($value)
 * @method static Builder<static>|Goal whereType($value)
 * @method static Builder<static>|Goal whereUpdatedAt($value)
 * @method static Builder<static>|Goal whereUserId($value)
 * @mixin Eloquent
 */
final class Goal extends Model
{
    /** @use HasFactory<GoalFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'topic_id',
        'user_id',
        'type',
        'target',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'type' => GoalTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
