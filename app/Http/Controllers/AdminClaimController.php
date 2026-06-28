<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class AdminClaimController extends Controller
{
    /**
     * Display a listing of claims.
     */
    public function index(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasPermission('claims.view')) {
            abort(403, 'No tienes permiso para ver las reclamaciones.');
        }

        $query = Claim::query();

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.claims.index', compact('claims'));
    }

    /**
     * Update the specified claim status.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('claims.manage')) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para gestionar reclamaciones.'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,resolved,dismissed'
        ]);

        $claim = Claim::findOrFail($id);
        $claim->status = $request->status;
        $claim->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado de la reclamación actualizado correctamente.'
            ]);
        }

        return redirect()->back()->with('success', 'Estado de la reclamación actualizado correctamente.');
    }

    /**
     * Remove the specified claim from storage.
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('claims.manage')) {
            abort(403, 'No tienes permiso para eliminar reclamaciones.');
        }

        $claim = Claim::findOrFail($id);
        $claim->delete();

        return redirect()->route('admin.claims.index')->with('success', 'Reclamación eliminada correctamente.');
    }
}
