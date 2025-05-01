<?php

declare(strict_types=1);

namespace App\Actions\Topics;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class UpdateTopicAction
{
    public function handle(Topic $topic, array $attributes): Topic
    {
        return DB::transaction(function () use ($topic, $attributes): Topic {
            $topic->update(
                [
                    'name' => $attributes['name'],
                    'slug' => Str::slug($attributes['name']),
                    'description' => $attributes['description'],
                    'unit' => $attributes['unit'],
                ]
            );

            $topic->refresh();

            return $topic;
        });
    }
}
