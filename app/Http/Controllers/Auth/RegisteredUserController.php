<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman Register.
     * (View tidak diubah sama sekali — hanya dihubungkan ke sini.)
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        // Sesuai permintaan: setelah berhasil register, arahkan ke halaman
        // Login (bukan langsung auto-login) supaya user login manual dulu.
        return redirect()->route('login')->with('status', 'Akun berhasil dibuat. Silakan login.');
    }
}
