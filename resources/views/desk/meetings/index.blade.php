@extends('layouts.app')

@section('content')
<h1>Lista spotkań</h1>
<a href="{{ route('meetings.create') }}" class="btn btn-primary">Dodaj spotkanie</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>Tytuł</th>
            <th>Opis</th>
            <th>Data rozpoczęcia</th>
            <th>Data zakończenia</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($meetings as $meeting)
        <tr>
            <td>{{ $meeting->title }}</td>
            <td>{{ $meeting->description }}</td>
            <td>{{ $meeting->start_time }}</td>
            <td>{{ $meeting->end_time }}</td>
            <td>
                <a href="{{ route('meetings.show', $meeting->id) }}" class="btn btn-info">Pokaż</a>
                <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-warning">Edytuj</a>
                <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST" style="display:inline;">
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
