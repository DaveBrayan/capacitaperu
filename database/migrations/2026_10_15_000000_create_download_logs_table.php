<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('download_logs')) {
            Schema::create('download_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('student_name');
                $table->string('student_dni');
                $table->string('format')->default('zip'); // zip or merged_pdf
                $table->integer('certificates_count')->default(1);
                $table->json('certificates_details')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('permissions')) {
            $exists = DB::table('permissions')->where('key', 'downloads.view')->exists();
            if (!$exists) {
                DB::table('permissions')->insert([
                    'key' => 'downloads.view',
                    'group' => 'Sistema',
                    'label' => 'Centro de Descargas',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('download_logs');
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->where('key', 'downloads.view')->delete();
        }
    }
};
