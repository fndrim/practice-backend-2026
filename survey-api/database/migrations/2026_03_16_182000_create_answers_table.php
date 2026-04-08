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
        $table->foreignId('survey_id')->constrained()->onDelete('cascade');
        $table->foreignId('question_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Кто ответил
        $table->text('answer_value'); // Здесь будем хранить ID варианта или текст
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
