<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------------------------------------
        // 1. Crear Usuario Admin (Si no existe)
        // ---------------------------------------------------------
        $adminEmail = 'test@example.com';
        $adminUsername = 'admin';
        
        // Verifica si existe por email O por username para evitar duplicados
        $existingUser = User::where('email', $adminEmail)
                            ->orWhere('username', $adminUsername)
                            ->first();

        if (!$existingUser) {
            User::factory()->create([
                'name' => 'Test User',
                'username' => $adminUsername, // Campo obligatorio único
                'email' => $adminEmail,
                'role' => 'superadmin',      // Rol con acceso total
                'status' => 'active',        // Estado activo
                // 'password' => Hash::make('password'), // El factory ya usa 'password' por defecto
            ]);
        }

        // ---------------------------------------------------------
        // 2. Inyectar Permisos del Sistema
        // ---------------------------------------------------------
        $perms = [
            // Promociones
            ['key' => 'promotions.view', 'group' => 'Marketing', 'label' => 'Ver Promociones'],
            ['key' => 'promotions.create', 'group' => 'Marketing', 'label' => 'Crear Promociones'],
            ['key' => 'promotions.edit', 'group' => 'Marketing', 'label' => 'Editar Promociones'],
            ['key' => 'promotions.delete', 'group' => 'Marketing', 'label' => 'Eliminar Promociones'],

            // Categorías
            ['key' => 'categories.view', 'group' => 'Académico', 'label' => 'Ver Categorías'],
            ['key' => 'categories.manage', 'group' => 'Académico', 'label' => 'Gestionar Categorías'],

            // Estudiantes
            ['key' => 'students.view', 'group' => 'Estudiantes', 'label' => 'Ver Estudiantes'],
            ['key' => 'students.manage', 'group' => 'Estudiantes', 'label' => 'Gestionar Estudiantes'],

            // Docentes
            ['key' => 'instructors.view', 'group' => 'Docentes', 'label' => 'Ver Docentes'],
            ['key' => 'instructors.manage', 'group' => 'Docentes', 'label' => 'Gestionar Docentes'],
            
            // Inscripciones
            ['key' => 'enrollments.view', 'group' => 'Ventas', 'label' => 'Ver Inscripciones'],
            ['key' => 'enrollments.manage', 'group' => 'Ventas', 'label' => 'Gestionar Inscripciones'],

            // Usuarios (Admin Staff)
            ['key' => 'users.view', 'group' => 'Administración', 'label' => 'Ver Staff'],
            ['key' => 'users.manage', 'group' => 'Administración', 'label' => 'Gestionar Staff'],

            // Certificados Individuales
            ['key' => 'individual-certificates.view', 'group' => 'Certificación', 'label' => 'Ver Certificados Individuales'],
            ['key' => 'individual-certificates.create', 'group' => 'Certificación', 'label' => 'Crear Certificados Individuales'],
            ['key' => 'individual-certificates.edit', 'group' => 'Certificación', 'label' => 'Editar Certificados Individuales'],
            ['key' => 'individual-certificates.delete', 'group' => 'Certificación', 'label' => 'Eliminar Certificados Individuales'],
            ['key' => 'individual-certificates.students', 'group' => 'Certificación', 'label' => 'Gestionar Alumnos de Certificados'],

            // Asesores
            ['key' => 'advisors.view', 'group' => 'Contacto', 'label' => 'Ver Asesores'],
            ['key' => 'advisors.manage', 'group' => 'Contacto', 'label' => 'Gestionar Asesores'],

            // Backups
            ['key' => 'backups.view', 'group' => 'Sistema', 'label' => 'Ver Backups'],
            ['key' => 'backups.manage', 'group' => 'Sistema', 'label' => 'Gestionar Backups'],

            // Editar Landing
            ['key' => 'landing.edit', 'group' => 'Sistema', 'label' => 'Editar Landing'],
        ];

        foreach ($perms as $perm) {
            // Insertar solo si la llave no existe para evitar duplicados
            if (!DB::table('permissions')->where('key', $perm['key'])->exists()) {
                DB::table('permissions')->insert([
                    'key' => $perm['key'],
                    'group' => $perm['group'],
                    'label' => $perm['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}