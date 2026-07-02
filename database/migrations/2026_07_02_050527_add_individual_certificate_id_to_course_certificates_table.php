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
        Schema::table('course_certificates', function (Blueprint $table) {
            $table->foreignId('individual_certificate_id')->nullable()->constrained('individual_certificates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_certificates', function (Blueprint $table) {
            $table->dropForeign(['individual_certificate_id']);
            $table->dropColumn('individual_certificate_id');
        });
    }
};
