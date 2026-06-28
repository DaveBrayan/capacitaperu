<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            // Relacionamos con la tabla 'students' según tu esquema
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); 
            
            // Aquí guardamos las rutas de los archivos (Array JSON)
            // Ej: ["storage/project_submissions/1/10/archivo.pdf"]
            $table->json('files'); 
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_submissions');
    }
};