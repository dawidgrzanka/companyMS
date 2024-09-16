@extends('layouts.app')

@section('content')
<h1>Ostrzeżenia o niskich stanach magazynowych</h1>

@if($products->isEmpty())
    <p>Brak produktów o niskim stanie magazynowym.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Dostępna ilość</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
