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
        Schema::create('course_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->string('title');
            $table->string('registration_number')->nullable();
            $table->integer('hours')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('certificate_template')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('individual_certificate_id')->nullable()->constrained('individual_certificates')->onDelete('set null');
            $table->foreignId('course_certificate_id')->nullable()->constrained('course_certificates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['individual_certificate_id']);
            $table->dropColumn('individual_certificate_id');
            $table->dropForeign(['course_certificate_id']);
            $table->dropColumn('course_certificate_id');
        });

        Schema::dropIfExists('course_certificates');
    }
};
