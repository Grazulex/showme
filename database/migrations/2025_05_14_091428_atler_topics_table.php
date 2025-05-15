<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->boolean('is_weight')->default(false);
        });

        $weightTopics = DB::table('topics')
            ->where('name', 'Poids')
            ->first();

        if ($weightTopics) {
            DB::table('topics')
                ->where('id', $weightTopics->id)
                ->update(['is_weight' => true]);
        }

    }
};
