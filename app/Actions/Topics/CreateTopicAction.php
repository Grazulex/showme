<?php

declare(strict_types=1);

namespace App\Actions\Topics;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final class CreateTopicAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, array $attributes): Topic
    {
        return DB::transaction(function () use ($user, $attributes) {
            return Topic::create(
                [
                    'user_id' => $user->id,
                    'name' => $attributes['name'],
                    'slug' => Str::slug($attributes['name']),
                    'description' => $attributes['description'],
                    'unit' => $attributes['unit'],
                ]
            );
        });
    }
}
