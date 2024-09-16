@extends('layouts.app')

@section('content')
<h1>Dodaj nową usługę</h1>
<br>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dodaj usługę</div>
            <div class="card-body">
                <form method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Nazwa</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Opis</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="price_net">Cena netto</label>
                        <input type="number" name="price_net" id="price_net" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="price_gross">Cena brutto</label>
                        <input type="number" name="price_gross" id="price_gross" class="form-control" step="0.01" required readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const priceNetto = document.getElementById('price_net');
        const priceBrutto = document.getElementById('price_gross');

        function updatePrices() {
            const netto = parseFloat(priceNetto.value) || 0;
            const brutto = netto * 1.06 * 1.08;
            priceBrutto.value = brutto.toFixed(2);
        }

        priceNetto.addEventListener('input', updatePrices);
    });
</script>

@endsection
