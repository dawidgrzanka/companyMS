@extends('layouts.app')

@section('content')
<h1>Szczegóły zlecenia</h1>
<br>
<div class="card">
    <div class="card-header">{{ $task->name }}</div>
    <div class="card-body">
        <p><strong>Opis:</strong> {{ $task->description }}</p>
        <p><strong>Klient:</strong> {{ $task->client->name }}</p>
        <p><strong>Status:</strong> {{ $task->status }}</p>

        @if($task->files->isNotEmpty())
            <h5>Pliki:</h5>
            <ul>
                @foreach($task->files as $file)
                    <li><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
