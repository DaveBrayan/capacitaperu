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
        Schema::table('individual_certificate_students', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('hours')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('registration_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_certificate_students', function (Blueprint $table) {
            $table->dropColumn(['is_custom', 'start_date', 'end_date', 'hours', 'issue_date', 'registration_number']);
        });
    }
};
