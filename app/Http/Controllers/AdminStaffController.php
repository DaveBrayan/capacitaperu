<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission; // <--- IMPORTANTE: Asegúrate de importar esto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminStaffController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['superadmin', 'admin', 'moderator'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        $avatars = [];
        $possiblePaths = [
            public_path('img/avatars/admin'),
            base_path('public/img/avatars/admin'),
            base_path('../public_html/img/avatars/admin'),
            base_path('public_html/img/avatars/admin'),
        ];
        
        $path = null;
        foreach ($possiblePaths as $p) {
            if (File::exists($p)) {
                $path = $p;
                break;
            }
        }

        if ($path) {
            foreach (File::files($path) as $file) {
                if (in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'webp'])) {
                    $avatars[] = 'img/avatars/admin/' . $file->getFilename();
                }
            }
        }

        if (empty($avatars)) {
            $avatars = [
                'https://ui-avatars.com/api/?name=Admin&background=213A57&color=fff',
                'https://ui-avatars.com/api/?name=Staff&background=random',
            ];
        }

        return view('admin.staff.index', compact('users', 'avatars'));
    }

    public function store(Request $request)
    {
        if ($request->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para asignar el rol de Super Administrador.'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,moderator',
            'avatar_url' => 'required|string',
        ]);

        $fullName = $request->name . ' ' . ($request->lastname ?? '');

        User::create([
            'name' => trim($fullName),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar_url' => $request->avatar_url,
            'status' => 'active',
            'pin' => null,
            'force_pass_reset' => true,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Usuario creado correctamente.')
            ->with('credentials_modal', [
                'name' => trim($fullName),
                'email' => $request->email,
                'password' => $request->password,
                'is_new' => true
            ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para modificar a un Super Administrador.']);
        }

        if ($request->role === 'superadmin' && $user->role !== 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para promover a un usuario al rol de Super Administrador.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'role' => 'required|in:superadmin,admin,moderator',
            'avatar_url' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $fullName = $request->name . ' ' . ($request->lastname ?? '');

        $user->name = trim($fullName);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->avatar_url = $request->avatar_url;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para restablecer la contraseña de un Super Administrador.']);
        }

        $newPass = Str::random(8); 
        $user->password = Hash::make($newPass);
        $user->force_pass_reset = true;
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Contraseña restablecida.')
            ->with('credentials_modal', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $newPass
            ]);
    }

    public function resetPin($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para eliminar el PIN de un Super Administrador.']);
        }

        $user->pin = null;
        $user->save();
        
        return redirect()->back()->with('success', 'PIN de seguridad eliminado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para eliminar a un Super Administrador.']);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }
        $user->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Usuario eliminado.');
    }

    // =========================================================
    // NUEVOS MÉTODOS PARA GESTIÓN DE PERMISOS
    // =========================================================

    public function getPermissions($id)
    {
        $user = User::with('permissions')->findOrFail($id);
        
        // Obtenemos todos los permisos disponibles agrupados por su categoría (group)
        $allPermissions = Permission::all()->groupBy('group');

        // Obtenemos solo los IDs de los permisos que el usuario YA tiene asignados
        $userPermissionIds = $user->permissions->pluck('id');

        return response()->json([
            'user' => $user,
            'grouped_permissions' => $allPermissions,
            'user_permissions' => $userPermissionIds
        ]);
    }

    public function syncPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar los permisos de un Super Administrador.'
            ], 403);
        }
        
        $request->validate([
            'permissions' => 'array', 
            'permissions.*' => 'exists:permissions,id' // Validar que los IDs existan en la tabla permissions
        ]);

        // Sync elimina los permisos que no estén en la lista y agrega los nuevos
        // Si no se envía nada (array vacío), se quitan todos los permisos.
        $user->permissions()->sync($request->permissions ?? []);

        return response()->json([
            'success' => true, 
            'message' => 'Permisos actualizados correctamente para ' . $user->name
        ]);
    }
}