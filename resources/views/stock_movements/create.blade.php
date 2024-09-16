@extends('layouts.app')

@section('content')
<h2>Rejestruj ruch magazynowy dla produktu: {{ $product->name }}</h2>

<form action="{{ route('stock.move') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <div>
        <label>Typ ruchu</label>
        <select name="movement_type">
            <option value="in">Przychód</option>
            <option value="out">Rozchód</option>
        </select>
    </div>

    <div>
        <label>Ilość</label>
        <input type="number" name="quantity" required>
    </div>

    <button type="submit">Zapisz</button>
</form>
@endsection
