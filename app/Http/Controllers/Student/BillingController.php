<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Obtenemos las inscripciones como historial de pagos
        $payments = $student->enrollments()
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.billing', compact('payments'));
    }
}