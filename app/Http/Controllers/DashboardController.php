<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total pengguna
        $totalUsers = User::count();

        // Hitung pengguna per role
        $usersByRole = Role::withCount('users')->get();

        // Ambil 5 pengguna terbaru
        $latestUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'usersByRole' => $usersByRole,
            'latestUsers' => $latestUsers
        ]);
    }
}
