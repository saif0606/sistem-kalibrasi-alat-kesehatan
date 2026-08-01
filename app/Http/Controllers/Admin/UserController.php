<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    // Existing query for $users (pelanggan/user list)
    $query = User::query();

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(10)->withQueryString();

    // Add this: query for admin accounts (used in Administrator tab)
    $admins = User::whereIn('role', ['admin', 'super_admin'])
                  ->latest()
                  ->get();

    return view('admin.users.index', compact('users', 'admins'));
}

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'in:user,admin,super_admin'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang aktif.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
    public function storeAdmin(Request $request)
{
    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'admin',
    ]);

    return redirect()->route('admin.users.index')
        ->with('success', 'Akun admin berhasil ditambahkan.');
}

public function destroyAdmin(User $admin)
{
    if ($admin->id === auth()->id() || $admin->role === 'super_admin') {
        return back()->with('error', 'Akun admin ini tidak dapat dihapus.');
    }

    $admin->delete();

    return redirect()->route('admin.users.index')
        ->with('success', 'Akun admin berhasil dihapus.');
}
}
