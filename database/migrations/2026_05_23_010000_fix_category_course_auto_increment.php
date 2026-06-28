<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('category_course')) {
            DB::statement('ALTER TABLE category_course MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keeping AUTO_INCREMENT is fine, so no down rollback statement is strictly needed.
    }
};
