@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dodaj Wydatek</h2>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf

        <!-- Typ dokumentu -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Pierwszy wiersz -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="type" class="form-label">Typ dokumentu</label>
                <select name="type" class="form-control" required>
                    <option value="Faktura" {{ old('type') == 'Faktura' ? 'selected' : '' }}>Faktura</option>
                    <option value="Faktura Proforma" {{ old('type') == 'Faktura Proforma' ? 'selected' : '' }}>Faktura Proforma</option>
                    <option value="Własny dokument nieksięgowy" {{ old('type') == 'Własny dokument nieksięgowy' ? 'selected' : '' }}>Własny dokument nieksięgowy</option>
                    <option value="Paragon" {{ old('type') == 'Paragon' ? 'selected' : '' }}>Paragon</option>
                    <option value="Paragon z NIP" {{ old('type') == 'Paragon z NIP' ? 'selected' : '' }}>Paragon z NIP</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="issue_date" class="form-label">Data wystawienia</label>
                <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date') }}" required>
            </div>
            <div class="col-md-4">
                <label for="sale_date" class="form-label">Data sprzedaży</label>
                <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date') }}" required>
            </div>
        </div>

         <!-- Drugi wiersz -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="number" class="form-label">Numer dokumentu</label>
                <input type="text" name="number" class="form-control" value="{{ old('number') }}" required>
            </div>
            <div class="col-md-6">
                <label for="issue_place" class="form-label">Miejsce wystawienia</label>
                <input type="text" name="issue_place" class="form-control" value="{{ old('issue_place') }}" required>
            </div>
        </div>        

        <!-- Trzeci wiersz -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="seller_type" class="form-label">Rodzaj sprzedawcy</label>
                <select name="seller_type" class="form-control" required>
                    <option value="Firma" {{ old('seller_type') == 'Firma' ? 'selected' : '' }}>Firma</option>
                    <option value="Osoba prywatna" {{ old('seller_type') == 'Osoba prywatna' ? 'selected' : '' }}>Osoba prywatna</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="seller_name" class="form-label">Nazwa firmy / Imię i nazwisko</label>
                <input type="text" name="seller_name" class="form-control" value="{{ old('seller_name') }}" required>
            </div>
            <div class="col-md-3">
                <label for="seller_nip" class="form-label">NIP (opcjonalnie)</label>
                <input type="text" name="seller_nip" class="form-control" value="{{ old('seller_nip') }}">
            </div>
        </div>

        <!-- Czwarty wiersz -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="seller_street" class="form-label">Ulica i nr</label>
                <input type="text" name="seller_street" class="form-control" value="{{ old('seller_street') }}" required>
            </div>
            <div class="col-md-3">
                <label for="seller_postal_code" class="form-label">Kod pocztowy</label>
                <input type="text" name="seller_postal_code" class="form-control" value="{{ old('seller_postal_code') }}" required>
            </div>
            <div class="col-md-3">
                <label for="seller_city" class="form-label">Miejscowość</label>
                <input type="text" name="seller_city" class="form-control" value="{{ old('seller_city') }}" required>
            </div>
        </div>

        <!-- Piąty wiersz -->
        <div class="mb-3">
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Ilość</th>
                        <th>Jednostka</th>
                        <th>Cena Netto</th>
                        <th>VAT %</th>
                        <th>Wartość Netto</th>
                        <th>Wartość Brutto</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(old('items', [[]]) as $index => $item)
                        <tr>
                            <td><input type="text" name="items[{{ $index }}][name]" class="form-control" value="{{ $item['name'] ?? '' }}" required></td>
                            <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" value="{{ $item['quantity'] ?? '' }}" step="0.01" required></td>
                            <td><input type="text" name="items[{{ $index }}][unit]" class="form-control" value="{{ $item['unit'] ?? '' }}" required></td>
                            <td><input type="number" name="items[{{ $index }}][net_price]" class="form-control net_price" value="{{ $item['net_price'] ?? '' }}" step="0.01" required></td>
                            <td>
                                <select name="items[{{ $index }}][vat_rate]" class="form-control vat_rate">
                                    <option value="23" {{ (isset($item['vat_rate']) && $item['vat_rate'] == '23') ? 'selected' : '' }}>23%</option>
                                    <option value="8" {{ (isset($item['vat_rate']) && $item['vat_rate'] == '8') ? 'selected' : '' }}>8%</option>
                                    <option value="7" {{ (isset($item['vat_rate']) && $item['vat_rate'] == '7') ? 'selected' : '' }}>7%</option>
                                    <option value="5" {{ (isset($item['vat_rate']) && $item['vat_rate'] == '5') ? 'selected' : '' }}>5%</option>
                                    <option value="0" {{ (isset($item['vat_rate']) && $item['vat_rate'] == '0') ? 'selected' : '' }}>0%</option>
                                    <option value="ZW" {{ (isset($item['vat_rate']) && $item['vat_rate'] == 'ZW') ? 'selected' : '' }}>ZW</option>
                                    <option value="NP" {{ (isset($item['vat_rate']) && $item['vat_rate'] == 'NP') ? 'selected' : '' }}>NP</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control net_value" value="{{ $item['net_value'] ?? '' }}" readonly></td>
                            <td><input type="text" class="form-control gross_value" value="{{ $item['gross_value'] ?? '' }}" readonly></td>
                            <td><button type="button" class="btn btn-danger remove-item">Usuń</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        

        <button type="button" id="add-item" class="btn btn-secondary">Dodaj pozycję</button>
        <button type="submit" class="btn btn-primary">Zapisz Wydatek</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let itemIndex = {{ count(old('items', [[]])) }}; // Indeks dla kolejnych pozycji

    // Funkcja do obliczania wartości netto i brutto
    function updateValues(row) {
        let quantity = parseFloat(row.querySelector(".quantity").value) || 0;
        let netPrice = parseFloat(row.querySelector(".net_price").value) || 0;
        let vatRate = row.querySelector(".vat_rate").value;

        let netValue = quantity * netPrice;
        let vatValue = (vatRate !== "ZW" && vatRate !== "NP") ? netValue * (parseFloat(vatRate) / 100) : 0;
        let grossValue = netValue + vatValue;

        row.querySelector(".net_value").value = netValue.toFixed(2);
        row.querySelector(".gross_value").value = grossValue.toFixed(2);
    }

    // Obsługa zmian w inputach
    document.querySelector("#items-table").addEventListener("input", function (event) {
        if (event.target.classList.contains("quantity") || event.target.classList.contains("net_price") || event.target.classList.contains("vat_rate")) {
            updateValues(event.target.closest("tr"));
        }
    });

    // Funkcja dodająca nowy wiersz
    function addRow() {
        let tableBody = document.querySelector("#items-table tbody");
        let newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td><input type="text" name="items[${itemIndex}][name]" class="form-control" required></td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" required></td>
            <td><input type="text" name="items[${itemIndex}][unit]" class="form-control" required></td>
            <td><input type="number" name="items[${itemIndex}][net_price]" class="form-control net_price" step="0.01" required></td>
            <td>
                <select name="items[${itemIndex}][vat_rate]" class="form-control vat_rate">
                    <option value="23">23%</option>
                    <option value="8">8%</option>
                    <option value="7">7%</option>
                    <option value="5">5%</option>
                    <option value="0">0%</option>
                    <option value="ZW">ZW</option>
                    <option value="NP">NP</option>
                </select>
            </td>
            <td><input type="text" class="form-control net_value" readonly></td>
            <td><input type="text" class="form-control gross_value" readonly></td>
            <td><button type="button" class="btn btn-danger remove-item">Usuń</button></td>
        `;

        tableBody.appendChild(newRow);
        itemIndex++;
    }

    // Obsługa przycisku dodawania nowej pozycji
    document.querySelector("#add-item").addEventListener("click", function () {
        addRow();
    });

    // Obsługa usuwania pozycji
    document.querySelector("#items-table").addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-item")) {
            event.target.closest("tr").remove();
        }
    });
});
</script>
@endpush
