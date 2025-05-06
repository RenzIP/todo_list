<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Role untuk merepresentasikan peran/role user
class Role extends Model
{
    // Hanya kolom nama yang bisa diisi massal
    protected $fillable = ['nama'];

    // Relasi one-to-many dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
