@extends('layouts.app')

@section('content')
<h1>{{ $offer->title }}</h1>
<p>ID: {{ $offer->id }}</p>
<p>Status: {{ $offer->status }}</p>
<p>Notatki: {{ $offer->notes }}</p>

<h2>Przedmioty</h2>
<table class="table">
    <thead>
        <tr>
            <th>Opis</th>
            <th>Ilość</th>
            <th>Cena</th>
        </tr>
    </thead>
    <tbody>
        @forelse($offer->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3">Brak przedmiotów</td>
        </tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('offers.index') }}" class="btn btn-secondary">Powrót do listy ofert</a>
<button onclick="window.print()" class="btn btn-primary">Drukuj ofertę</button>
@endsection
