@extends('layouts.app')

@section('content')
<h1>{{ $invoice->title }}</h1>
<p>ID: {{ $invoice->id }}</p>
<p>Status: {{ $invoice->status }}</p>
<p>Stawka VAT: {{ $invoice->vat_rate }}%</p>
<p>Notatki: {{ $invoice->notes }}</p>

<h2>Przedmioty</h2>
<table class="table">
    <thead>
        <tr>
            <th>Opis</th>
            <th>Ilość</th>
            <th>Cena</th>
            <th>Stawka VAT</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoice->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->vat_rate }}%</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Brak przedmiotów</td>
        </tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('invoices.index') }}" class="btn btn-secondary">Powrót do listy faktur</a>
<button onclick="window.print()" class="btn btn-primary">Drukuj fakturę</button>
@endsection
