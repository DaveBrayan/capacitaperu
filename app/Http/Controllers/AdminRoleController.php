<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    // Devuelve los datos necesarios para el modal vía AJAX o carga inicial
    public function index()
    {
        $roles = Role::with('permissions')->get();
        
        // Agrupamos permisos por 'group' para mostrarlos ordenados en el modal
        $permissions = Permission::all()->groupBy('group');

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    // Actualizar permisos de un rol
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        // Validar que vengan permisos (array de IDs)
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Sincronizar (Borra los anteriores y pone los nuevos)
        $role->permissions()->sync($request->permissions);

        return response()->json(['success' => true, 'message' => 'Permisos actualizados correctamente.']);
    }

    // Crear un nuevo rol personalizado (Opcional)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'label' => 'required'
        ]);

        $role = Role::create([
            'name' => \Illuminate\Support\Str::slug($request->name),
            'label' => $request->label
        ]);

        return response()->json(['success' => true, 'role' => $role]);
    }
}