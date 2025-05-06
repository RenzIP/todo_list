<?php 
namespace App\Http\Controllers; 

use App\Models\User; 
use App\Models\Role; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller 
{ 
    public function showRegisterForm() 
    { 
        return view('auth.register'); 
    } 

    public function register(Request $request) 
    { 
        $validatedData = $request->validate([ 
            'nama' => 'required|max:255', 
            'username' => [ 
                'required',   
                'min:3',      
                'max:255',    
                'unique:users'  
            ], 
            'email' => 'required|email|unique:users',  
            'password' => 'required|min:6|max:255|confirmed' 
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

        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['nama' => 'admin']);
        $userRole = Role::firstOrCreate(['nama' => 'user']);

        $validatedData['id_role'] = $userRole->id; 
        $validatedData['password'] = Hash::make($validatedData['password']); 
        User::create($validatedData); 

        return redirect('/login')->with('success', 'Registrasi Berhasil! Silahkan Login'); 
    } 

    public function showLoginForm() 
    { 
        return view('auth.login'); 
    } 
    public function login(Request $request) 
    { 
        $credentials = $request->validate([ 
            'login' => 'required|string', // Bisa email atau username 
            'password' => 'required|string' 
        ], [ 
            'login.required' => 'Email atau username harus diisi', 
            'password.required' => 'Password harus diisi' 
        ]); 
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) 
? 'email' : 'username'; 
        $authCredentials = [ 
            $loginType => $credentials['login'], 
            'password' => $credentials['password'] 
        ]; 
        if (Auth::attempt($authCredentials)) { 
            $request->session()->regenerate(); 
            return Auth::user()->role->nama === 'admin' 
                ? redirect()->route('dashboard')->with('success', 'Selamat 
datang Admin!') 
                : redirect()->route('tasks.index')->with('success', 'Login 
berhasil!'); 
        } 
        return back() 
            ->withInput($request->only('login')) 
            ->withErrors([ 
                'login' => 'Email/Username atau password salah' 
            ]); 
    } 

    public function logout(Request $request) 
    { 
        Auth::logout(); // Logout user 
        $request->session()->invalidate(); // Invalidate session 
        $request->session()->regenerateToken(); // Regenerate CSRF token 
        return redirect('/login')->with('success', 'Logout berhasil'); 
    } 
}
