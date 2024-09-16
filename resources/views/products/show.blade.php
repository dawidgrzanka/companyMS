@extends('layouts.app')

@section('content')
<h1>Szczegóły produktu: {{ $product->name }}</h1>

<p><strong>Cena zakupu netto:</strong> {{ $product->purchase_price_netto }}</p>
<p><strong>Cena zakupu brutto:</strong> {{ $product->purchase_price_brutto }}</p>
<p><strong>Cena sprzedaży netto:</strong> {{ $product->sale_price_netto }}</p>
<p><strong>Cena sprzedaży brutto:</strong> {{ $product->sale_price_brutto }}</p>
<p><strong>Marża:</strong> {{ $product->margin }}%</p>
<p><strong>Dostępna ilość:</strong> {{ $product->stock }}</p>

<a href="{{ route('stock.create', $product->id) }}">Dodaj ruch magazynowy</a>

<h2>Historia ruchów magazynowych</h2>
<table>
    <thead>
        <tr>
            <th>Typ ruchu</th>
            <th>Ilość</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movements as $movement)
        <tr>
            <td>{{ $movement->movement_type == 'in' ? 'Przychód' : 'Rozchód' }}</td>
            <td>{{ $movement->quantity }}</td>
            <td>{{ $movement->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
