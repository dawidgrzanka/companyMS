@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($client) ? 'Edytuj Klienta' : 'Dodaj Nowego Klienta' }}</h1>

    <form action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
        @csrf
        @if(isset($client))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="first_name">Imię</label>
            <input type="text" name="first_name" class="form-control" value="{{ isset($client) ? $client->first_name : old('first_name') }}">
        </div>

        <div class="form-group">
            <label for="last_name">Nazwisko</label>
            <input type="text" name="last_name" class="form-control" value="{{ isset($client) ? $client->last_name : old('last_name') }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ isset($client) ? $client->email : old('email') }}">
        </div>

        <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text" name="phone" class="form-control" value="{{ isset($client) ? $client->phone : old('phone') }}">
        </div>

        <div class="form-group">
            <label for="company_name">Firma</label>
            <input type="text" name="company_name" class="form-control" value="{{ isset($client) ? $client->company_name : old('company_name') }}">
        </div>

        <div class="form-group">
            <label for="nip">NIP:</label>
            <input type="text" name="nip" class="form-control" value="{{ old('nip', $client->nip ?? '') }}">
        </div>

        <div class="form-group">
            <label for="address">Adres</label>
            <textarea name="address" class="form-control">{{ isset($client) ? $client->address : old('address') }}</textarea>
        </div>
        <br>
        <button type="submit" class="btn btn-outline-success">{{ isset($client) ? 'Zapisz' : 'Dodaj' }}</button>
    </form>
</div>
@endsection
