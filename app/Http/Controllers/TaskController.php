<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

// Controller untuk mengelola operasi CRUD pada Task
class TaskController extends Controller
{
    // Menampilkan daftar tugas dengan filter dan pencarian
    public function index(Request $request)
    {
        // Query dasar untuk tasks milik user yang login
        $query = Task::where('id_user', auth()->id());
        
        // Menerapkan filter pencarian jika ada
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        
        // Menerapkan filter status jika ada
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Mengambil data tugas dan mengurutkannya
        $tasks = $query->orderBy('created_at', 'desc')->get();
        
        // Menghitung jumlah tugas per status
        $counters = [
            'all' => $tasks->count(),
            'tertunda' => $tasks->where('status', 'tertunda')->count(),
            'selesai' => $tasks->where('status', 'selesai')->count()
        ];
        
        return view('user.tasks', compact('tasks', 'counters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:tertunda,selesai'
        ], [
            'judul.required' => 'Judul tugas harus diisi',
            'judul.max' => 'Judul tugas maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi tugas harus diisi',
            'deadline.required' => 'Tenggat waktu harus diisi',
            'deadline.after_or_equal' => 'Tenggat waktu tidak boleh kurang dari hari ini',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid'
        ]);

        // Pastikan task dibuat dengan id_user yang sedang login
        $validatedData['id_user'] = auth()->id();

        Task::create($validatedData);

        return redirect('/tasks')->with('success', 'Tugas baru berhasil ditambahkan!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Pastikan task dimiliki oleh user yang sedang login
        $task = Task::where('id_user', auth()->id())->findOrFail($id);
        
        // Validasi input
        $validatedData = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'deadline' => 'required|date',
            'status' => 'required|in:tertunda,selesai'
        ], [
            'judul.required' => 'Judul tugas harus diisi',
            'judul.max' => 'Judul tugas maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi tugas harus diisi',
            'deadline.required' => 'Tenggat waktu harus diisi',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid'
        ]);

        // Update task
        $task->update($validatedData);

        // Redirect dengan pesan sukses
        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Validasi kepemilikan task
            $task = Task::where('id_user', auth()->id())
                       ->where('id', $id)
                       ->firstOrFail();

            // Hapus task
            $task->delete();

            return redirect()
                ->route('tasks.index')
                ->with('success', 'Tugas berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->route('tasks.index')
                ->with('error', 'Gagal menghapus tugas. Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * Toggle the status of the specified task.
     */
    public function toggleStatus($id)
    {
        $task = Task::where('id_user', auth()->id())->findOrFail($id);
        $task->status = $task->status === 'selesai' ? 'tertunda' : 'selesai';
        $task->save();
        
        return redirect()->back()->with('success', 'Status tugas berhasil diubah');
    }
}
