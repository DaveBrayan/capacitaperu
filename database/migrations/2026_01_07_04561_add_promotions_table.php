<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable(); // Código opcional (ej: VERANO2024)
            
            // Configuración del Descuento
            $table->enum('discount_type', ['percent', 'fixed']); // Porcentaje o Monto Fijo
            $table->decimal('value', 8, 2); // Valor del descuento (ej: 10% o 50Bs)
            
            // Alcance (A qué aplica)
            $table->enum('target_type', ['all', 'category', 'course']);
            $table->json('target_ids')->nullable(); // IDs de categorías o cursos seleccionados
            
            // Vigencia
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
};