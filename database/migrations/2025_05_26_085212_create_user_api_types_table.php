<?php

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
        Schema::create('user_api_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider');
            $table->string('tag');
            $table->text('prompt');
            $table->float('temperature')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->integer('api_count')->nullable();
            $table->integer('max_tokens')->nullable();
            $table->string('mode_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_api_types');
    }
};
