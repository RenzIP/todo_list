<?php

namespace App\Http\Controllers;

// Import kelas-kelas yang diperlukan
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Controller untuk menangani autentikasi user
class AuthController extends Controller
{
    /**
     * Menampilkan halaman form register
     * Method ini akan merender view auth.register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses data registrasi user baru
     * @param Request $request berisi semua data form
     */
    public function register(Request $request)
    {
        // Validasi data input dari form
        $validatedData = $request->validate([
            'nama' => 'required|max:255', // Nama wajib diisi, maksimal 255 karakter
            'username' => [
                'required',  // Username wajib diisi
                'min:3',     // Minimal 3 karakter
                'max:255',   // Maksimal 255 karakter
                'unique:users' // Harus unik di tabel users
            ],
            'email' => 'required|email|unique:users', // Email wajib diisi, format email, dan unik
            'password' => 'required|min:6|max:255|confirmed' // Password wajib diisi, min 6 karakter, dan harus dikonfirmasi
        ], [
            // Pesan error kustom untuk setiap validasi
            'nama.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        // Cek apakah role user sudah ada
        $userRole = Role::where('nama', 'user')->first();
        
        // Jika belum ada role sama sekali, buat role admin dan user
        if (!$userRole) {
            Role::create(['nama' => 'admin']);
            $userRole = Role::create(['nama' => 'user']);
        }

        // Set role user untuk user baru
        $validatedData['id_role'] = $userRole->id;
        
        // Hash password sebelum disimpan ke database
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Buat user baru dengan data yang sudah divalidasi
        User::create($validatedData);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Registrasi Berhasil! Silahkan Login');
    }

    /**
     * Menampilkan form login
     * Method ini akan merender view auth.login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login user
     * @param Request $request berisi credentials login
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'login' => 'required|string', // Bisa email atau username
            'password' => 'required|string'
        ], [
            'login.required' => 'Email atau username harus diisi',
            'password.required' => 'Password harus diisi'
        ]);

        // Cek apakah input adalah email atau username
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Susun credentials untuk authentication
        $authCredentials = [
            $loginType => $credentials['login'],
            'password' => $credentials['password']
        ];

        // Coba melakukan autentikasi
        if (Auth::attempt($authCredentials)) {
            $request->session()->regenerate();
            
            // Redirect langsung berdasarkan role
            return Auth::user()->role->nama === 'admin'
                ? redirect()->route('dashboard')->with('success', 'Selamat datang Admin!')
                : redirect()->route('tasks.index')->with('success', 'Login berhasil!');
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return back()
            ->withInput($request->only('login'))
            ->withErrors([
                'login' => 'Email/Username atau password salah'
            ]);
    }

    /**
     * Proses logout user
     * @param Request $request untuk akses session
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token
        return redirect('login')->with('success', 'Anda telah berhasil logout');
    }
}
