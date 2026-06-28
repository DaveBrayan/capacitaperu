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
        Schema::create('individual_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('registration_number')->nullable();
            $table->integer('hours')->nullable();
            
            // Relaciones con las tablas existentes
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Diseño del certificado (JSON) e Imagen de portada (opcional, para index)
            $table->json('certificate_template')->nullable();
            $table->string('cover_image')->nullable();
            
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('individual_certificates');
    }
};
