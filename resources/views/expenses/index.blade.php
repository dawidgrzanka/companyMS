@extends('layouts.app')

@section('content')
<h1>Lista wydatków</h1>
<br>
<div class="row mb-3">
@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif
<!-- Formularz wyboru okresu -->
<form method="GET" action="{{ route('expenses.index') }}" class="row mb-3">
    <div class="col-md-3">
        <select name="year" class="form-select" required>
            <option value="">Wybierz rok</option>
            @for($i = 2020; $i <= now()->year; $i++)
                <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="col-md-3">
        <select name="month" class="form-select" required>
            <option value="">Wybierz miesiąc</option>
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}" {{ request()->get('month') == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromDate(null, $month, 1)->locale('pl')->monthName }}  <!-- Miesiąc po polsku -->
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtruj</button>
    </div>
</form>

<button onclick="window.location='{{ route('expenses.create') }}'" type="button" class="btn btn-outline-success">{{ __('Dodaj Wydatek') }}</button>

<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th scope="col">Typ</th>
            <th scope="col">Numer</th>
            <th scope="col">Data wystawienia</th>
            <th scope="col">Data sprzedaży</th>
            <th scope="col">Sprzedawca</th>
            <th scope="col">Wartość netto</th>
            <th scope="col">Wartość VAT</th>
            <th scope="col">Wartość brutto</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $expense)
        <tr>
            <td>{{ $expense->type }}</td>
            <td>{{ $expense->number }}</td>
            <td>{{ $expense->issue_date }}</td>
            <td>{{ $expense->sale_date }}</td>
            <td>{{ $expense->seller_name }}</td>
            <td>{{ number_format($expense->items->sum('net_value'), 2) }} zł</td>
            <td>{{ number_format($expense->items->sum(function ($item) {
                return $item->gross_value - $item->net_value;  // Różnica między wartością brutto a netto
            }), 2) }} zł</td>
            <td>{{ number_format($expense->items->sum('gross_value'), 2) }} zł</td>
            <td>
                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-outline-info">Szczegóły</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Tabela sum netto i VAT -->
<div class="row justify-content-end">
    <div class="col-md-4">
        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">Suma netto (za okres)</th>
                    <th scope="col">Suma VAT (za okres)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($monthlyNetSum, 2) }} zł</td>
                    <td>{{ number_format($monthlyVatSum, 2) }} zł</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    {{ $expenses->links() }}
</div>
@endsection
