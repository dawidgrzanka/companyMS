@extends('layouts.app')

@section('content')
<h1>Lista notatek</h1>
<a href="{{ route('notes.create') }}" class="btn btn-primary">Dodaj notatkę</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Tytuł</th>
            <th>Treść</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notes as $note)
        <tr>
            <td>{{ $note->title }}</td>
            <td>{{ $note->content }}</td>
            <td>
                <a href="{{ route('notes.show', $note->id) }}" class="btn btn-info">Pokaż</a>
                <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning">Edytuj</a>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
