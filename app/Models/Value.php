<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\ValueFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $topic_id
 * @property int $user_id
 * @property float $value
 * @property float $diff_with_last
 * @property string $color
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property Topic $topic
 * @property User $user
 *
 * @method static ValueFactory factory($count = null, $state = [])
 * @method static Builder<static>|Value newModelQuery()
 * @method static Builder<static>|Value newQuery()
 * @method static Builder<static>|Value query()
 * @method static Builder<static>|Value whereColor($value)
 * @method static Builder<static>|Value whereCreatedAt($value)
 * @method static Builder<static>|Value whereDiffWithLast($value)
 * @method static Builder<static>|Value whereId($value)
 * @method static Builder<static>|Value whereTopicId($value)
 * @method static Builder<static>|Value whereUpdatedAt($value)
 * @method static Builder<static>|Value whereUserId($value)
 * @method static Builder<static>|Value whereValue($value)
 *
 * @mixin Eloquent
 */
final class Value extends Model
{
    /** @use HasFactory<ValueFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'topic_id',
        'user_id',
        'value',
        'diff_with_last',
        'color',
        'created_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'diff_with_last' => 'decimal:2',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
