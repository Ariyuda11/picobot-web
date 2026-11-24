<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

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

    // Menampilkan halaman pendaftaran akun pasien
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses pendaftaran akun pasien (role selalu 'pasien')
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis bawah (_), dan tanda hubung (-).',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ], [
            'name' => 'nama lengkap',
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'pasien',
        ]);

        return redirect()
            ->route('login')
            ->withInput(['username' => $validated['username'], 'role' => 'pasien'])
            ->with('success', 'Akun berhasil dibuat. Silakan masuk. Admin akan menghubungkan akun Anda dengan data pasien.');
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
