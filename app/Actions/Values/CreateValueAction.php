<?php

declare(strict_types=1);

namespace App\Actions\Values;

use App\Models\User;
use App\Models\Value;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateValueAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, array $attributes): Value
    {
        return DB::transaction(function () use ($user, $attributes) {
            return Value::create([
                'user_id' => $user->id,
                'topic_id' => $attributes['topic_id'],
                'value' => $attributes['value'],
                'created_at' => $attributes['created_at'],
            ]);
        });
    }
}
