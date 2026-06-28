<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar nota a las lecciones (Quizzes modulares)
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->integer('score')->nullable()->after('completed');
        });

        // Agregar nota al examen final (Inscripción)
        Schema::table('enrollments', function (Blueprint $table) {
            $table->integer('final_score')->nullable()->after('progress_percent');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn('score');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('final_score');
        });
    }
};