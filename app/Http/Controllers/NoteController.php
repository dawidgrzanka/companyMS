<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return view('desk.notes.index', compact('notes'));
    }

    public function create()
    {
        return view('desk.notes.create');
    }

    public function store(Request $request)
    {
        Note::create($request->all());
        return redirect()->route('notes.index');
    }

    public function show(Note $note)
    {
        return view('desk.notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        return view('desk.notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $note->update($request->all());
        return redirect()->route('notes.index');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index');
    }
}
