<?php

declare(strict_types=1);

namespace App\Actions\Topics;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final class UpdateTopicAction
{
    /**
     * @throws Throwable
     */
    public function handle(Topic $topic, array $attributes): Topic
    {
        return DB::transaction(function () use ($topic, $attributes): Topic {
            $topic->update(
                [
                    'name' => $attributes['name'],
                    'slug' => Str::slug($attributes['name']),
                    'description' => $attributes['description'],
                    'unit' => $attributes['unit'],
                    'is_weight' => $attributes['is_weight'],
                ]
            );

            $topic->refresh();

            return $topic;
        });
    }
}
