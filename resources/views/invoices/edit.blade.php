@extends('layouts.app')

@section('content')
<h1>Edytuj fakturę</h1>

<form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ $invoice->title }}" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-control" required>
            <option value="created" {{ $invoice->status == 'created' ? 'selected' : '' }}>Utworzona</option>
            <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>Wysłana</option>
            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Opłacona</option>
            <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Nieopłacona</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="vat_rate" class="form-label">Stawka VAT</label>
        <input type="number" id="vat_rate" name="vat_rate" class="form-control" value="{{ $invoice->vat_rate }}" required>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notatki</label>
        <textarea id="notes" name="notes" class="form-control">{{ $invoice->notes }}</textarea>
    </div>
    <div class="mb-3">
        <label for="items" class="form-label">Przedmioty</label>
        <div id="items">
            @foreach($invoice->items as $index => $item)
            <div class="item">
                <select name="items[{{ $index }}][id]" class="form-control mb-2">
                    <option value="">-- Wybierz przedmiot z bazy danych --</option>
                    @foreach($availableItems as $availableItem)
                    <option value="{{ $availableItem->id }}" {{ $availableItem->id == $item->id ? 'selected' : '' }}>{{ $availableItem->description }}</option>
                    @endforeach
                </select>
                <input type="text" name="items[{{ $index }}][description]" placeholder="Opis" class="form-control mb-2" value="{{ $item->description }}">
                <input type="number" name="items[{{ $index }}][quantity]" placeholder="Ilość" class="form-control mb-2" value="{{ $item->quantity }}">
                <input type="number" name="items[{{ $index }}][price]" placeholder="Cena" class="form-control mb-2" value="{{ $item->price }}">
                <input type="number" name="items[{{ $index }}][vat_rate]" placeholder="Stawka VAT" class="form-control mb-2" value="{{ $item->vat_rate }}">
            </div>
            @endforeach
        </div>
        <button type="button" id="add-item" class="btn btn-secondary">Dodaj przedmiot</button>
    </div>
    <button type="submit" class="btn btn-primary">Zaktualizuj fakturę</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = {{ $invoice->items->count() }};
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
