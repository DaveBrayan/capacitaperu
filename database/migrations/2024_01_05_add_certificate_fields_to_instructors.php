<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->string('certificate_name')->nullable()->after('bio'); // Nombre con grado (Ing., Lic.)
            $table->string('signature_url')->nullable()->after('certificate_name'); // Ruta de la firma
        });
    }

    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn(['certificate_name', 'signature_url']);
        });
    }
};