@extends('layouts.app')

@section('content')
<h1>Lista usług</h1>
<br>
<div class="row mb-3">
    <div class="col-md-3">
        <form method="GET" action="{{ route('services.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control me-2" placeholder="Wyszukaj usługę..." style="border-radius: 0.5rem">
            <button class="btn btn-outline-primary" type="submit">Szukaj</button>
        </form>
    </div>
</div>
<button onclick="window.location='{{ route('services.create') }}'" type="button" class="btn btn-outline-success">{{ __('Dodaj Usługę') }}</button>

<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th scope="col">Nazwa</th>
            <th scope="col">Opis</th>
            <th scope="col">Cena netto</th>
            <th scope="col">Cena brutto</th>
            <th scope="col">Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($services as $service)
        <tr>
            <td scope="row">{{ $service->name }}</td>
            <td>{{ $service->description }}</td>
            <td>{{ $service->price_net }} zł</td>
            <td>{{ $service->price_gross }} zł</td>
            <td>
                <a href="{{ route('services.edit', $service->id) }}">Edytuj</a> |
                <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
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
