<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Store a newly created claim in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        Claim::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Tu reclamación ha sido recibida con éxito! Nos pondremos en contacto a la brevedad.'
        ]);
    }
}
