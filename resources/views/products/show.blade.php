@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    <br>
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('products.index') }}" class="btn btn-outline-success mb-3">Powrót do listy produktów</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Kwota zakupu (Netto/Brutto)</th>
                        <td>{{ number_format($product->purchase_price_netto, 2) }} zł/ {{ number_format($product->purchase_price_brutto, 2) }} zł</td>
                    </tr>
                    <tr>
                        <th scope="row">Kwota sprzedaży (Netto/Brutto)</th>
                        <td>{{ number_format($product->sale_price_netto, 2) }} zł / {{ number_format($product->sale_price_brutto, 2) }} zł</td>
                    </tr>
                    <tr>
                        <th scope="row">Marża</th>
                        <td>{{ $product->margin }}%</td>
                    </tr>
                    <tr>
                        <th scope="row">Ilość</th>
                        <td>{{ $product->stock }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning">Edytuj</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">Usuń</button>
                </form>
            </div>

            <br>
            <hr>
            <br>

            <h4>Ruchy magazynowe</h4>
            @if($product->stockMovements->count())
                <ul class="list-group">
                    @foreach($product->stockMovements as $movement)
                        <li class="list-group-item">
                            <strong>Data:</strong> {{ $movement->created_at->format('d M Y H:i') }}<br>
                            <strong>Typ:</strong> 
                                @if($movement->movement_type === 'in')
                                    Przyjęcie
                                @elseif($movement->movement_type === 'out')
                                    Wydanie
                                @endif<br>
                            <strong>Ilość:</strong> {{ $movement->quantity }}<br>
                            <strong>Opis:</strong> {{ $movement->remarks }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Brak wpisów</p>
            @endif
        </div>
    </div>
</div>
@endsection
