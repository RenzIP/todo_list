<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

// Controller untuk mengelola data user oleh admin
class KelolaUserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::with('role')->get();
        return view('dashboard.kelolauser.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.kelolauser.create', [
            'roles' => $roles
        ]);
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users',
            'email' => 'required|email:dns|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
            'id_role' => 'required|exists:roles,id'
        ], [
            'nama.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 6 karakter',
            'id_role.required' => 'Role harus dipilih',
            'id_role.exists' => 'Role tidak valid'
        ]);

        try {
            // Hash password
            $validatedData['password'] = Hash::make($validatedData['password']);
            
            // Create user baru dengan role yang dipilih
            User::create($validatedData);

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menambahkan user!')
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('dashboard.kelolauser.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        // Validasi input
        $rules = [
            'nama' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users,username,'.$id,
            'email' => 'required|email:dns|unique:users,email,'.$id,
            'id_role' => 'required|exists:roles,id'
        ];

        // Validasi password hanya jika diisi
        if($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(6)];
        }

        $validatedData = $request->validate($rules, [
            'nama.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 6 karakter',
            'id_role.required' => 'Role harus dipilih',
            'id_role.exists' => 'Role tidak valid'
        ]);

        try {
            // Update password jika diisi
            if($request->filled('password')) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                // Hapus key password jika tidak diisi
                unset($validatedData['password']);
            }
            
            // Update user
            $user->update($validatedData);

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat mengupdate user!')
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return redirect()->route('users.index')
                           ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menghapus user!');
        }
    }
}
