@extends('layouts.app')

@section('content')
<h1>Edytuj notatkę</h1>

<form action="{{ route('notes.update', $note->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ $note->title }}" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Treść</label>
        <textarea id="content" name="content" class="form-control" required>{{ $note->content }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Zaktualizuj notatkę</button>
</form>
@endsection
