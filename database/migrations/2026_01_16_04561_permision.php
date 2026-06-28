<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabla de Permisos (Catálogo de acciones posibles)
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ej: users.create
            $table->string('group'); // Ej: Usuarios, Ventas
            $table->string('label'); // Ej: Crear Usuarios
            $table->timestamps();
        });

        // 2. Tabla Pivote (Usuario <-> Permisos)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'permission_id']);
        });

        // --- SEEDER DE PERMISOS INICIALES ---
        $perms = [
            // Usuarios
            ['key' => 'users.view', 'group' => 'Gestión de Usuarios', 'label' => 'Ver lista de usuarios'],
            ['key' => 'users.create', 'group' => 'Gestión de Usuarios', 'label' => 'Crear nuevos usuarios'],
            ['key' => 'users.edit', 'group' => 'Gestión de Usuarios', 'label' => 'Editar información de usuarios'],
            ['key' => 'users.delete', 'group' => 'Gestión de Usuarios', 'label' => 'Eliminar usuarios'],
            ['key' => 'users.permissions', 'group' => 'Gestión de Usuarios', 'label' => 'Gestionar Permisos'],
            
            // Cursos
            ['key' => 'courses.view', 'group' => 'Académico', 'label' => 'Ver cursos'],
            ['key' => 'courses.create', 'group' => 'Académico', 'label' => 'Crear/Editar cursos'],
            ['key' => 'courses.publish', 'group' => 'Académico', 'label' => 'Publicar cursos'],
            ['key' => 'courses.grade', 'group' => 'Académico', 'label' => 'Calificar tareas'],
            
            // Finanzas
            ['key' => 'finance.view', 'group' => 'Finanzas', 'label' => 'Ver reportes financieros'],
            ['key' => 'finance.refund', 'group' => 'Finanzas', 'label' => 'Emitir reembolsos'],
            
            // Sistema
            ['key' => 'settings.view', 'group' => 'Sistema', 'label' => 'Ver configuración'],
        ];
        DB::table('permissions')->insert($perms);
    }

    public function down()
    {
        // Desactivamos restricciones para evitar el error 1451
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('permission_user');
        // Agregamos estas líneas por seguridad para limpiar residuos anteriores
        Schema::dropIfExists('permission_role'); 
        Schema::dropIfExists('roles');
        
        Schema::dropIfExists('permissions');
        
        Schema::enableForeignKeyConstraints();
    }
};