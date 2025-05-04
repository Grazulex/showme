<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
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
 */
final class Value extends Model
{
    /** @use HasFactory<\Database\Factories\ValueFactory> */
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
