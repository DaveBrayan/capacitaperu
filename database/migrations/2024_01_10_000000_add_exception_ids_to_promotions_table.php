<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo ejecutar si la tabla existe (evita error si se crea en el futuro 2026)
        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                if (!Schema::hasColumn('promotions', 'exception_ids')) {
                    // Quitamos 'after' para evitar errores de dependencia de columnas
                    $table->json('exception_ids')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                if (Schema::hasColumn('promotions', 'exception_ids')) {
                    $table->dropColumn('exception_ids');
                }
            });
        }
    }
};