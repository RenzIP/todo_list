<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan nama default (tasks)
    protected $table = 'tasks';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'id_user',
        'judul',
        'deskripsi',
        'deadline',
        'status',
    ];

    protected $casts = [ 
        'deadline' => 'datetime' 
    ]; 
    protected $attributes = [ 
        'status' => 'tertunda' 
    ]; 
    public function user() 
    { 
        return $this->belongsTo(User::class, 'id_user'); 
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        });
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });
        $query->when($filters['user'] ?? false, function ($query, $user) {
            $query->where('id_user', $user);
        });
    }
    public function scopeCountByStatus($query, $status)
    {
        return $query->where('status', $status)->count();
    }
    public function scopeCountByUser($query, $userId)
    {
        return $query->where('id_user', $userId)->count();
    }
}