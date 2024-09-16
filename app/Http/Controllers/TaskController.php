<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('files')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:in_progress,completed',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $task = Task::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task_files', 'public');
                TaskFile::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zlecenie dodane.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:in_progress,completed',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $task->update($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task_files', 'public');
                TaskFile::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zlecenie zaktualizowane.');
    }

    public function destroy(Task $task)
    {
        foreach ($task->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Zlecenie usunięte.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }


}
