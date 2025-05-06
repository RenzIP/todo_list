<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Ambil 3 user pertama untuk ditugaskan pada tugas-tugas
        $users = User::take(3)->get();

        // Loop untuk membuat data tugas
        foreach ($users as $user) {
            Task::create([
                'id_user' => $user->id,
                'judul' => 'Task ' . Str::random(5),
                'deskripsi' => 'Deskripsi untuk task ' . Str::random(10),
                'deadline' => now()->addDays(rand(1, 30)),
                'status' => rand(0, 1) ? 'tertunda' : 'selesai',
            ]);
        }
    }
}
