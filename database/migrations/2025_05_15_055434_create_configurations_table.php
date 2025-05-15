<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateConfigurationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_weight', 'weight_id')
                ->constrained('topics')
                ->cascadeOnDelete();
            $table->foreignId('topic_calorie_in', 'calorie_in_id')
                ->constrained('topics')
                ->cascadeOnDelete();
            $table->foreignId('topic_calorie_out', 'calorie_out_id')
                ->constrained('topics')
                ->cascadeOnDelete();
        });
    }
}
