<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model User untuk merepresentasikan pengguna sistem
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama',      // Nama lengkap user
        'username',  // Username untuk login
        'email',     // Email user
        'password',  // Password terenkripsi
        'id_role',   // Role/peran user
    ];

    /**
     * Daftar atribut yang harus disembunyikan saat model dikonversi ke array/JSON
     * Melindungi data sensitif seperti password
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Daftar atribut yang harus di-cast ke tipe data tertentu
     * Mengkonversi kolom database ke tipe data yang sesuai
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel roles
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}