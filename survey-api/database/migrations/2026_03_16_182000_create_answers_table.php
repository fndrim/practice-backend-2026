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
    Schema::create('answers', function (Blueprint $table) {
        $table->id();
        // Связь с вопросом
        $table->foreignId('question_id')->constrained()->onDelete('cascade');
        $table->text('answer_value'); // Само значение ответа
        $table->text('answer_text');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
