<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::all();
        return view('desk.meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('desk.meetings.create');
    }

    public function store(Request $request)
    {
        Meeting::create($request->all());
        return redirect()->route('meetings.index');
    }

    public function show(Meeting $meeting)
    {
        return view('desk.meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        return view('desk.meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $meeting->update($request->all());
        return redirect()->route('meetings.index');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index');
    }
}
