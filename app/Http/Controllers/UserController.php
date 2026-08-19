<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.manajemen-user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:'.User::ROLE_SUPERADMIN.','.User::ROLE_ADMIN],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        // Mencegah user menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Mencegah penghapusan akun superadmin (Protected)
        if ($user->isSuperadmin()) {
            return redirect()->back()->with('error', 'Akun Superadmin tidak dapat dihapus!');
        }

        $user->delete();

        session()->flash('deleted_user', [
            'id' => $user->hash,
            'name' => $user->name,
        ]);

        return redirect()->back();
    }

    public function restore(User $user)
    {
        $user->restore();

        return redirect()->back()->with('success', "Akun '{$user->name}' berhasil dipulihkan!");
    }
}
