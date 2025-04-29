<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();

            $table->string('name')->max(255)->min(3);
            $table->string('slug')->max(255)->min(3);
            $table->string('description')->max(255)->min(3);
            $table->string('unit')->max(255)->min(3)->nullable();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unique(
                ['name', 'user_id'],
                'topics_name_user_id_unique'
            );

            $table->timestamps();
        });
    }
};
