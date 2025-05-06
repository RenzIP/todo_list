<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Task untuk merepresentasikan tabel tasks dalam database
class Task extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal 
    protected $fillable = [
        'judul',         // Judul task
        'deskripsi',     // Deskripsi detail task
        'deadline',      // Tenggat waktu task
        'status',        // Status task (tertunda/selesai) 
        'id_user'        // ID user pemilik task
    ];

    // Mengubah tipe data kolom deadline menjadi datetime
    protected $casts = [
        'deadline' => 'datetime'
    ];

    // Nilai default untuk kolom status
    protected $attributes = [
        'status' => 'tertunda'
    ];

    // Relasi ke model User (many-to-one)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Method untuk pencarian dan filter task
    public function scopeSearch($query, $filters)
    {
        return $query
            // Filter berdasarkan kata kunci pencarian
            ->when($filters['search'] ?? false, function($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%');
            })
            // Filter berdasarkan status
            ->when($filters['status'] ?? false, function($query, $status) {
                $query->where('status', $status);
            })
            // Filter berdasarkan user yang login
            ->where('id_user', auth()->id());
    }

    // Method untuk menghitung jumlah task berdasarkan status
    public function scopeCountByStatus($query, $status = null)
    {
        return $query->where('id_user', auth()->id())
            ->when($status, function($query, $status) {
                $query->where('status', $status);
            })
            ->count();
    }
}