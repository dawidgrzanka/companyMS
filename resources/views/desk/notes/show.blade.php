@extends('layouts.app')

@section('content')
<h1>{{ $note->title }}</h1>
<p>Treść: {{ $note->content }}</p>

<a href="{{ route('notes.index') }}" class="btn btn-secondary">Powrót do listy notatek</a>
@endsection
