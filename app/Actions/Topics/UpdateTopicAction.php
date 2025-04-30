<?php

declare(strict_types=1);

namespace App\Actions\Topics;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;

final class UpdateTopicAction
{
    public function handle(Topic $topic, array $attributes): Topic
    {
        return DB::transaction(function () use ($topic, $attributes) {
            $topic->update(
                [
                    'name' => $attributes['name'],
                    'description' => $attributes['description'],
                    'unit' => $attributes['unit'],
                ]
            );

            return $topic;
        });
    }
}
