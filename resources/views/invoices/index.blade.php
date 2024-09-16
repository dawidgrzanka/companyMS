@extends('layouts.app')

@section('content')
<h1>Lista faktur</h1>
<a href="{{ route('invoices.create') }}" class="btn btn-primary">Dodaj fakturę</a>
<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tytuł</th>
            <th>Status</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->title }}</td>
            <td>{{ $invoice->status }}</td>
            <td>
                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">Pokaż</a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Edytuj</a>
                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
                <a href="{{ route('invoices.exportToPDF', $invoice->id) }}" class="btn btn-secondary">Eksportuj do PDF</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
