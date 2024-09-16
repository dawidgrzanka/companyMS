@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dodaj Ruch Magazynowy dla {{ $product->name }}</h1>
    <br>
    
    <!-- Form to create a stock movement -->
    <form method="POST" action="{{ route('stock.move') }}">
        @csrf
        
        <!-- Hidden input for product ID -->
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        
        <div class="mb-3">
            <label for="movement_type" class="form-label">Typ Ruchu</label>
            <select id="movement_type" name="movement_type" class="form-select @error('movement_type') is-invalid @enderror">
                <option value="in">Przyjęcie</option>
                <option value="out">Wydanie</option>
            </select>
            @error('movement_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Ilość</label>
            <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Uwagi</label>
            <textarea id="remarks" name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
            @error('remarks')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-outline-primary">Zapisz Ruch</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Powrót do Listy Produktów</a>
    </form>
</div>
@endsection
