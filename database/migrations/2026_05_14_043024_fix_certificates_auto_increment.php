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
        // Usamos SQL directo porque change() puede ser problemático con auto-increment en algunas versiones de Laravel/MySQL
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE certificates MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE certificates MODIFY id BIGINT UNSIGNED');
    }
};
