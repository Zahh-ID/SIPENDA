<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login-operator'); // Menggunakan view baru
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string|max:255', // Username Operator
            'password' => 'required',
        ]);
        
        $attempt = Auth::guard('operator')->attempt([
            'username' => $credentials['identifier'],
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Login Operator berhasil. Mengalihkan...',
                'redirect' => route('operator.dashboard'),
            ]);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Username atau Password salah.'], 401);
    }
}