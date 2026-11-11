<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login Admin dan Pasien
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'role' => ['required', 'in:admin,pasien'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginData = [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ];

        if (Auth::attempt($loginData)) {

            // Memperbarui session setelah login berhasil
            $request->session()->regenerate();

            // Mengarahkan pengguna ke dashboard
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'username' => 'Username, password, atau jenis pengguna tidak sesuai.',
        ])->onlyInput('username', 'role');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
