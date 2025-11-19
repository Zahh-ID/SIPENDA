<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login-admin'); // Menggunakan view baru
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|email|max:255', // Email Admin Dinas
            'password' => 'required',
        ]);
        
        $attempt = Auth::guard('web')->attempt([
            'email' => $credentials['identifier'],
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Login Admin Dinas berhasil. Mengalihkan...',
                'redirect' => route('admin.dashboard'),
            ]);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Email atau Password salah.'], 401);
    }
}