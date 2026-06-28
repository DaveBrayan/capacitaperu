<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'courses',
            'advisors',
            'promotions',
            'individual_certificates',
            'enrollments',
            'categories',
            'instructors',
            'students'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            });

            // Asignar el primer usuario (ID: 1) a los registros existentes
            try {
                DB::table($tableName)->whereNull('user_id')->update(['user_id' => 1]);
            } catch (\Exception $e) {
                // Ignorar si falla por alguna razón en bases de datos específicas
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'courses',
            'advisors',
            'promotions',
            'individual_certificates',
            'enrollments',
            'categories',
            'instructors',
            'students'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
