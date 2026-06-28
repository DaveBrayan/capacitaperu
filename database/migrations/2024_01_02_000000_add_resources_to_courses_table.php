<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Solo intenta agregar la columna si la tabla 'courses' YA existe
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                // Verificamos si la columna ya existe para evitar duplicados
                if (!Schema::hasColumn('courses', 'resources')) {
                    // ELIMINAMOS '->after("benefits")' para evitar el error de columna inexistente
                    $table->json('resources')->nullable(); 
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'resources')) {
                    $table->dropColumn('resources');
                }
            });
        }
    }
};