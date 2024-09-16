@extends('layouts.app')

@section('content')
<h1>Historia ruchów magazynowych dla produktu: {{ $product->name }}</h1>

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
