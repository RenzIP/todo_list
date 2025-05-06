<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan nama default (roles)
    protected $table = 'roles';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = ['nama'];

    // Relasi dengan model User
    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
