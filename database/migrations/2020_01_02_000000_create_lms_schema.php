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
        // 1. USUARIOS DEL SISTEMA (ADMINS/STAFF)
        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Datos de Acceso
            $table->string('name');                 
            $table->string('username')->unique();   
            $table->string('email')->unique();   
            $table->timestamp('email_verified_at')->nullable();   
            $table->string('password');
            
            // Datos Específicos del Sistema
            $table->string('role')->default('admin'); 
            
            // CORRECCIÓN AQUÍ: Quitamos el limite ', 4' para guardar el hash encriptado
            $table->string('pin')->nullable();     
            
            $table->string('avatar_url')->nullable();
            $table->string('status')->default('active'); 
            
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. INSTRUCTORES (DOCENTES)
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('specialty')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 3. ESTUDIANTES
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            
            // AGREGADO: Columna PIN para estudiantes también
            $table->string('pin')->nullable();

            $table->string('phone')->nullable();
            $table->string('ci_nit')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bolivia');
            $table->string('avatar_url')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 4. TABLA CURSOS (Ajustada para Borradores)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            
            // AHORA SON NULLABLE (OPCIONALES)
            $table->text('description')->nullable(); 
            $table->text('short_description')->nullable(); 
            $table->decimal('price', 10, 2)->default(0); // Precio sí es obligatorio (default 0)
            $table->string('cover_image')->nullable();
            
            // Instructor opcional al inicio
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            
            $table->string('level')->nullable()->default('Básico'); 
            $table->string('category')->nullable();
            
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('featured')->default(false); 
            
            // Campos JSON
            $table->json('evaluation_config')->nullable();
            $table->json('certificate_template')->nullable();
            $table->json('attendance_config')->nullable();

            $table->timestamps();
        });

        // 5. TEMAS / MÓDULOS
        Schema::create('course_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title'); 
            $table->text('summary')->nullable(); 
            $table->integer('order_index')->default(0); 
            $table->timestamps();
        });

        // 6. LECCIONES
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('course_topics')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->enum('type', ['video', 'text', 'quiz', 'zoom', 'assignment'])->default('video'); 
            $table->text('content')->nullable(); 
            $table->string('video_source')->nullable(); 
            $table->string('video_url')->nullable(); 
            $table->integer('duration_minutes')->default(0);
            $table->json('attachments')->nullable(); 
            $table->integer('order_index')->default(0); 
            $table->boolean('is_preview')->default(false); 
            $table->timestamps();
        });

        // 7. INSCRIPCIONES
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->decimal('price_paid', 10, 2);
            $table->timestamp('enrolled_at');
            $table->integer('progress_percent')->default(0); 
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        // 8. PROGRESO DETALLADO
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('course_lessons')->onDelete('cascade');
            $table->boolean('completed')->default(true);
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
        });

        // 9. CERTIFICADOS
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('course_id')->constrained('courses');
            $table->timestamp('issued_at');
            $table->string('download_url')->nullable();
            $table->timestamps();
        });
        
        // 10. WISHLIST
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();
        });
        
        // 11. TABLA PARA SESIONES
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_topics');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('students');
        Schema::dropIfExists('instructors');
        Schema::dropIfExists('users');
    }
};