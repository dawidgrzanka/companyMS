@extends('layouts.app')

@section('content')
<h1>Lista zleceń</h1>
<br>
<a href="{{ route('tasks.create') }}" class="btn btn-outline-success">Dodaj Zlecenie</a>
<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th scope="col">Nazwa</th>
            <th scope="col">Opis</th>
            <th scope="col">Klient</th>
            <th scope="col">Status</th>
            <th scope="col">Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tasks as $task)
        <tr>
            <td>{{ $task->name }}</td>
            <td>{{ $task->description }}</td>
            <td>
                @if($task->client->company_name)
                    {{ $task->client->company_name }}
                @else
                    {{ $task->client->first_name }} {{ $task->client->last_name }}
                @endif
            </td>
            <td>
                @if($task->status === 'in_progress')
                    W trakcie realizacji
                @elseif($task->status === 'completed')
                    Zakończona realizacja
                @endif
            </td>
            <td>
                <a href="{{ route('tasks.show', $task->id) }}">Szczegóły</a> |
                <a href="{{ route('tasks.edit', $task->id) }}">Edytuj</a> |
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Usuń</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
