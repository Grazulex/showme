<?php

declare(strict_types=1);

use App\Enums\GoalTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('type')->default(GoalTypeEnum::increase);
            $table->decimal('target', 10, 2)->default(0);

            $table->dateTime('ended_at')->nullable();

            $table->timestamps();
        });
    }
};
