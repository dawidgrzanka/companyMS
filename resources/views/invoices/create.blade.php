@extends('layouts.app')

@section('content')
<h1>Dodaj nową fakturę</h1>

<form action="{{ route('invoices.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-control" required>
            <option value="created">Utworzona</option>
            <option value="sent">Wysłana</option>
            <option value="paid">Opłacona</option>
            <option value="unpaid">Nieopłacona</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="vat_rate" class="form-label">Stawka VAT</label>
        <input type="number" id="vat_rate" name="vat_rate" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notatki</label>
        <textarea id="notes" name="notes" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="items" class="form-label">Przedmioty</label>
        <div id="items">
            <div class="item">
                <select name="items[0][id]" class="form-control mb-2">
                    <option value="">-- Wybierz przedmiot z bazy danych --</option>
                    @foreach($availableItems as $availableItem)
                    <option value="{{ $availableItem->id }}">{{ $availableItem->description }}</option>
                    @endforeach
                </select>
                <input type="text" name="items[0][description]" placeholder="Opis" class="form-control mb-2">
                <input type="number" name="items[0][quantity]" placeholder="Ilość" class="form-control mb-2">
                <input type="number" name="items[0][price]" placeholder="Cena" class="form-control mb-2">
                <input type="number" name="items[0][vat_rate]" placeholder="Stawka VAT" class="form-control mb-2">
            </div>
        </div>
        <button type="button" id="add-item" class="btn btn-secondary">Dodaj przedmiot</button>
    </div>
    <button type="submit" class="btn btn-primary">Dodaj fakturę</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', function () {
        const itemsDiv = document.getElementById('items');
        const newItemDiv = document.createElement('div');
        newItemDiv.classList.add('item');
        newItemDiv.innerHTML = `
            <select name="items[${itemIndex}][id]" class="form-control mb-2">
                <option value="">-- Wybierz przedmiot z bazy danych --</option>
                @foreach($availableItems as $availableItem)
                <option value="{{ $availableItem->id }}">{{ $availableItem->description }}</option>
                @endforeach
            </select>
            <input type="text" name="items[${itemIndex}][description]" placeholder="Opis" class="form-control mb-2">
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Ilość" class="form-control mb-2">
            <input type="number" name="items[${itemIndex}][price]" placeholder="Cena" class="form-control mb-2">
            <input type="number" name="items[${itemIndex}][vat_rate]" placeholder="Stawka VAT" class="form-control mb-2">
        `;
        itemsDiv.appendChild(newItemDiv);
        itemIndex++;
    });
});
</script>
@endsection
