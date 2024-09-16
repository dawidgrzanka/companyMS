@extends('layouts.app')

@section('content')
<h1>Dodaj nową notatkę</h1>

<form action="{{ route('notes.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Treść</label>
        <textarea id="content" name="content" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Dodaj notatkę</button>
</form>
@endsection
