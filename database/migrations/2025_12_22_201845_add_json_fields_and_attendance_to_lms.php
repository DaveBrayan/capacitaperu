<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar campos JSON a la tabla CURSOS (si no existen)
        Schema::table('courses', function (Blueprint $table) {
            // Verifica si no existen antes de agregarlos para evitar errores al correr migrate varias veces
            if (!Schema::hasColumn('courses', 'evaluation_config')) {
                $table->json('evaluation_config')->nullable()->after('status'); // Config de examen/proyecto
            }
            if (!Schema::hasColumn('courses', 'certificate_template')) {
                $table->json('certificate_template')->nullable()->after('evaluation_config'); // Diseño visual
            }
            if (!Schema::hasColumn('courses', 'attendance_config')) {
                $table->json('attendance_config')->nullable()->after('certificate_template'); // Preguntas y config global
            }
        });

        // 2. Crear tabla de REGISTROS DE ASISTENCIA
        if (!Schema::hasTable('attendance_records')) {
            Schema::create('attendance_records', function (Blueprint $table) {
                $table->id();
                
                // Relación con el curso
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                
                // Relación con el módulo (Tema). Puede ser null si la asistencia es general del curso.
                $table->foreignId('topic_id')->nullable()->constrained('course_topics')->onDelete('cascade');
                
                // Datos del estudiante (Texto plano o relación si quieres forzar login, 
                // pero pediste que fuera público con DNI)
                $table->string('student_dni'); 
                $table->string('student_name')->nullable(); // Opcional, si quieres capturarlo en el form
                
                // Las respuestas del formulario (JSON)
                $table->json('answers')->nullable();
                
                // Metadatos de seguridad
                $table->ipAddress('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                
                $table->timestamps();
                
                // Índices para búsquedas rápidas
                $table->index(['course_id', 'topic_id']);
                $table->index('student_dni');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['evaluation_config', 'certificate_template', 'attendance_config']);
        });
    }
};