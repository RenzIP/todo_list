<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('id_user', auth()->id());
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $tasks = $query->orderBy('created_at', 'desc')->get();
        $counters = [
            'all' => $tasks->count(),
            'tertunda' => $tasks->where('status', 'tertunda')->count(),
            'selesai' => $tasks->where('status', 'selesai')->count(),
        ];
        $tasks = Task::where('id_user', auth()->id())->get();
        return view('tasks.index', compact('tasks'));
        return view('user.tasks', compact('tasks', 'counters'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:tertunda,selesai',
        ], [
            'judul.required' => 'Judul tugas harus diisi',
            'judul.max' => 'Judul tugas maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi tugas harus diisi',
            'deadline.required' => 'Tenggat waktu harus diisi',
            'deadline.after_or_equal' => 'Tenggat waktu tidak boleh kurang dari hari ini',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        $validatedData['id_user'] = auth()->id();
        Task::create($validatedData);

        return redirect('/tasks')->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('id_user', auth()->id())->findOrFail($id);

        $validatedData = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'deadline' => 'required|date',
            'status' => 'required|in:tertunda,selesai',
        ], [
            'judul.required' => 'Judul tugas harus diisi',
            'judul.max' => 'Judul tugas maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi tugas harus diisi',
            'deadline.required' => 'Tenggat waktu harus diisi',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $task = Task::where('id_user', auth()->id())->where('id', $id)->firstOrFail();
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $task = Task::where('id_user', auth()->id())->findOrFail($id);
        $task->status = $task->status === 'selesai' ? 'tertunda' : 'selesai';
        $task->save();

        return redirect()->back()->with('success', 'Status tugas berhasil diubah!');
    }
}