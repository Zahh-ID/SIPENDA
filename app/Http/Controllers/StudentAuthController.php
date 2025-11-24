<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Menggunakan view yang sudah ada
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|digits:10', // NISN
            'password' => 'required',
        ]);
        
        // Custom attempt karena field identifier adalah 'nisn'
        $attempt = Auth::guard('student')->attempt([
            'nisn' => $credentials['identifier'],
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            $user = Auth::guard('student')->user();
            return response()->json([
                'status' => 'success', 
                'message' => 'Login Siswa berhasil. Mengalihkan...',
                'redirect' => route('student.dashboard'),
                'identifier' => $user->nisn
            ]);
        }
        
        return response()->json(['status' => 'error', 'message' => 'NISN atau Password salah.'], 401);
    }
}