<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Configuration extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'topic_weight',
        'topic_calorie_in',
        'topic_calorie_out',
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
}
