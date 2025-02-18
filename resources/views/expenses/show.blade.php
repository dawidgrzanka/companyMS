@extends('layouts.app')

@section('content')
<h1>Szczegóły wydatku</h1>

<div class="card">
    <div class="card-body">
        <p class="card-title">Numer: {{ $expense->number }}</p>
        <p><strong>Typ:</strong> {{ $expense->type }}</p>
        <p><strong>Data wystawienia:</strong> {{ $expense->issue_date }}</p>
        <p><strong>Data sprzedaży:</strong> {{ $expense->sale_date }}</p>
        <p><strong>Sprzedawca:</strong> {{ $expense->seller_name }}</p>
        <p><strong>Wartość netto:</strong> {{ number_format($expense->items->sum('net_value'), 2) }} zł</p>
        <p><strong>Wartość VAT:</strong> {{ number_format($expense->items->sum(function ($item) {
            return $item->gross_value - $item->net_value;
        }), 2) }} zł</p>
        <p><strong>Wartość brutto:</strong> {{ number_format($expense->items->sum('gross_value'), 2) }} zł</p>

        <h3>Pozycje wydatku</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nazwa</th>
                    <th scope="col">Ilość</th>
                    <th scope="col">Cena netto</th>
                    <th scope="col">VAT</th>
                    <th scope="col">Cena brutto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expense->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->net_value, 2) }} zł</td>
                    <td>{{ number_format($item->gross_value - $item->net_value, 2) }} zł</td>
                    <td>{{ number_format($item->gross_value, 2) }} zł</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Powrót do listy</a>

        <!-- Form do usunięcia wydatku -->
        <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wydatek?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Usuń wydatek</button>
        </form>
    </div>
</div>
@endsection
