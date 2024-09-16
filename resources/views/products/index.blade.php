@extends('layouts.app')

@section('content')
<h1>Lista produktów</h1>
<br>
<div class="row mb-3">
    <div class="col-md-3">
        <form method="GET" action="{{ route('products.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control me-2" placeholder="Wyszukaj produkt..." style="border-radius: 0.5rem">
            <button class="btn btn-outline-primary" type="submit">Szukaj</button>
        </form>
    </div>
    <div class="col-md-2">
        <a href="{{ route('products.index', ['sort' => 'stock', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="btn btn-outline-secondary w-100" style="font-size: 0.8rem">
            Sortuj po dostępnej ilości ({{ request('direction') === 'asc' ? 'Rosnąco' : 'Malejąco' }})
        </a>
    </div>
</div>
<button onclick="window.location='{{ route('products.create') }}'"type="button" class="btn btn-outline-success">{{ __('Dodaj Produkt') }}</button>

<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th scope="col">Nazwa</th>
            <th scope="col">Cena zakupu (netto/brutto)</th>
            <th scope="col">Cena sprzedaży (netto/brutto)</th>
            <th scope="col">Marża</th>
            <th scope="col">Dostępna ilość</th>
            <th scope="col">Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td scope="row">{{ $product->name }}</td>
            <td>{{ $product->purchase_price_netto }} zł / {{ $product->purchase_price_brutto }} zł</td>
            <td>{{ $product->sale_price_netto }} zł / {{ $product->sale_price_brutto }} zł</td>
            <td>{{ $product->margin }}%</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('products.show', $product->id) }}">Szczegóły</a> |
                <a href="{{ route('products.edit', $product->id) }}">Edytuj</a> |
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
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
