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
        $tables = [
            'users', 'instructors', 'students', 'courses', 'course_topics', 
            'course_lessons', 'enrollments', 'lesson_progress', 'wishlists',
            'advisors', 'settings'
        ];

        foreach ($tables as $table) {
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `{$table}` MODIFY id BIGINT UNSIGNED AUTO_INCREMENT");
            } catch (\Exception $e) {
                // Silently skip if table doesn't exist or column is different
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es estrictamente necesario quitar el auto-increment en el rollback 
        // ya que es el estado deseado por defecto de Laravel.
    }
};
