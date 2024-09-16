@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista Klientów</h1>
    <br>
    <button onclick="window.location='{{ route('clients.create') }}'"type="button" class="btn btn-outline-success">{{ __('Dodaj Nowego Klienta') }}</button>
    
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Firma</th>
                <th>NIP</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->first_name }}</td>
                <td>{{ $client->last_name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->company_name }}</td>
                <td>{{ $client->nip }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-info btn-sm">Pokaż</a>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-warning btn-sm">Edytuj</a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
