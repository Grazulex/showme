<?php

declare(strict_types=1);

namespace App\Actions\Values;

use App\Models\Topic;
use App\Models\Value;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateValueAction
{
    /**
     * @throws Throwable
     */
    public function handle(Value $value, array $attributes): Value
    {

        Topic::findOrFail($attributes['topic_id']);

        return DB::transaction(function () use ($value, $attributes): Value {
            $value->update([
                'topic_id' => $attributes['topic_id'],
                'value' => $attributes['value'],
                'created_at' => $attributes['created_at'],
            ]);

            $value->refresh();

            return $value;
        });
    }
}
