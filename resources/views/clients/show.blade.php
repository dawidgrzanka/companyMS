@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Szczegóły klienta: {{ $client->name }}
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label style="font-weight: bold;">Imię i nazwisko:</label>
                        <p>{{ $client->first_name }} {{ $client->last_name }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold;">Nazwa firmy:</label>
                        <p>{{ $client->company_name }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold;">NIP:</label>
                        <p>{{ $client->nip }}</p> <!-- Wyświetlamy numer NIP -->
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold;">Email:</label>
                        <p>{{ $client->email }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold;">Telefon:</label>
                        <p>{{ $client->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold;">Adres:</label>
                        <p>{{ $client->address }}</p>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">Edytuj</a>
                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć tego klienta?')">Usuń</button>
                        </form>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Powrót do listy klientów</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
