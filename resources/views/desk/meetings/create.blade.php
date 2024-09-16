@extends('layouts.app')

@section('content')
<h1>Dodaj nowe spotkanie</h1>

<form action="{{ route('meetings.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Opis</label>
        <textarea id="description" name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Data rozpoczęcia</label>
        <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">Data zakończenia</label>
        <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Dodaj spotkanie</button>
</form>
@endsection
