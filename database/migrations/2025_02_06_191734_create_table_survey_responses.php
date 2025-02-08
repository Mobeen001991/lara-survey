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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // one response per user
            $table->tinyInteger('q1')->nullable();
            $table->tinyInteger('q2')->nullable();
            $table->tinyInteger('q3')->nullable();
            $table->tinyInteger('q4')->nullable();
            $table->tinyInteger('q5')->nullable();
            $table->tinyInteger('q6')->nullable();
            $table->tinyInteger('q7')->nullable();
            $table->tinyInteger('q8')->nullable();
            $table->tinyInteger('q9')->nullable();
            $table->tinyInteger('q10')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
