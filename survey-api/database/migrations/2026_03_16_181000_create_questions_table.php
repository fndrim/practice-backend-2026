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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // Эта строка создает связь: вопрос принадлежит опросу
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('question_text');
            // Типы вопросов: текст, один вариант, несколько вариантов
            $table->enum('type', ['text', 'single', 'multiple']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
