@extends('layouts.app')

@section('content')
<h1>{{ $meeting->title }}</h1>
<p>Opis: {{ $meeting->description }}</p>
<p>Data rozpoczęcia: {{ $meeting->start_time }}</p>
<p>Data zakończenia: {{ $meeting->end_time }}</p>

<a href="{{ route('meetings.index') }}" class="btn btn-secondary">Powrót do listy spotkań</a>
@endsection
