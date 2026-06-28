<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            // Usamos integer para el ID del módulo (topic_id) ya que tu tabla es course_topics
            $table->unsignedBigInteger('topic_id'); 
            $table->json('answers')->nullable(); // Guardamos las respuestas del formulario
            $table->timestamp('attended_at');
            $table->timestamps();

            // Evitar duplicados: Un estudiante solo marca asistencia una vez por módulo
            $table->unique(['student_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};