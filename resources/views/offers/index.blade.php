@extends('layouts.app')

@section('content')
<h1>Lista ofert</h1>
<a href="{{ route('offers.create') }}" class="btn btn-primary">Dodaj ofertę</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tytuł</th>
            <th>Status</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($offers as $offer)
        <tr>
            <td>{{ $offer->id }}</td>
            <td>{{ $offer->title }}</td>
            <td>{{ $offer->status }}</td>
            <td>
                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-info">Pokaż</a>
                <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-warning">Edytuj</a>
                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" style="display:inline;">
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
