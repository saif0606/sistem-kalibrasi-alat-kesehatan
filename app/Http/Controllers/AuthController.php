<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $role = Auth::user()->role;
            $defaultRedirect = ($role === 'admin' || $role === 'super_admin') 
                ? route('admin.dashboard') 
                : route('user.calibrations.index');

            return redirect()->intended($defaultRedirect);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Tampilkan form register
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user', // default role
        ]);

        Auth::login($user);

        return redirect()->route('user.calibrations.index')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Helper redirect berdasarkan role
    private function redirectByRole()
    {
        $role = Auth::user()->role;
        if ($role === 'admin' || $role === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.calibrations.index');
    }
}
