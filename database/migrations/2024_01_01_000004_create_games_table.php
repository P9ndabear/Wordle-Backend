<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opponent_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('word');
            $table->enum('result', ['win', 'lose', 'draw'])->nullable();
            $table->enum('status', ['pending', 'active', 'finished'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
}; 