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
        Schema::table('courses', function (Blueprint $blueprint) {
            // Carga horaria (usamos integer o string dependiendo de si solo son números)
            if (!Schema::hasColumn('courses', 'duration')) {
                $blueprint->integer('duration')->nullable()->after('price');
            }

            // Metadatos para Fecha de Inicio y Modalidad (Tipo JSON)
            if (!Schema::hasColumn('courses', 'metadata')) {
                $blueprint->json('metadata')->nullable()->after('duration');
            }

            // Resultados de aprendizaje (Lo que aprenderás)
            if (!Schema::hasColumn('courses', 'learning_outcomes')) {
                $blueprint->json('learning_outcomes')->nullable()->after('metadata');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['duration', 'metadata', 'learning_outcomes']);
        });
    }
};