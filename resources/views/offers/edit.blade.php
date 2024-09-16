@extends('layouts.app')

@section('content')
<h1>Edytuj ofertę</h1>

<form action="{{ route('offers.update', $offer->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ $offer->title }}" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-control" required>
            <option value="accepted" {{ $offer->status == 'accepted' ? 'selected' : '' }}>Zaakceptowana</option>
            <option value="rejected" {{ $offer->status == 'rejected' ? 'selected' : '' }}>Odrzucona</option>
            <option value="pending" {{ $offer->status == 'pending' ? 'selected' : '' }}>Oczekująca</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notatki</label>
        <textarea id="notes" name="notes" class="form-control">{{ $offer->notes }}</textarea>
    </div>
    <div class="mb-3">
        <label for="items" class="form-label">Przedmioty</label>
        <div id="items">
            @foreach($offer->items as $index => $item)
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
            </div>
            @endforeach
        </div>
        <button type="button" id="add-item" class="btn btn-secondary">Dodaj przedmiot</button>
    </div>
    <button type="submit" class="btn btn-primary">Zaktualizuj ofertę</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = {{ $offer->items->count() }};
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
        `;
        itemsDiv.appendChild(newItemDiv);
        itemIndex++;
    });
});
</script>
@endsection
