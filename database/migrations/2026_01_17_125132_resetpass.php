<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregamos el campo a la tabla de STAFF (users)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'force_pass_reset')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('force_pass_reset')->default(false)->after('password');
            });
        }

        // Agregamos el campo a la tabla de ESTUDIANTES (students)
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'force_pass_reset')) {
            Schema::table('students', function (Blueprint $table) {
                $table->boolean('force_pass_reset')->default(false)->after('password');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('force_pass_reset');
            });
        }
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('force_pass_reset');
            });
        }
    }
};