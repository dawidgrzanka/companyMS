@extends('layouts.app')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<h1>Szczegóły zlecenia</h1>
<br>
<div class="card">
    <div class="card-header">{{ $task->name }}</div>
    <div class="card-body">
        <p><strong>Opis:</strong> {{ $task->description }}</p>
        <p><strong>Klient:</strong> {{ $task->client->name }}</p>
        <p><strong>Planowany budżet:</strong> {{ $task->planned_material_budget }} PLN</p>
        <p><strong>Aktualne wydatki na materiały:</strong> {{ number_format($current_material_expenses, 2) }} PLN</p>
        <p><strong>Pozostały budżet do wykorzystania:</strong> {{ number_format($remaining_budget, 2) }} PLN</p>
        <p><strong>Status:</strong> {{ $task->status }}</p>

        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th scope="col">Nazwa materiału</th>
                    <th scope="col">Ilość</th>
                    <th scope="col">Dostępna ilość w magazynie</th>
                    <th scope="col">Cena zakupu netto</th>
                    <th scope="col">Cena sprzedaży netto</th>
                    <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task->materialUsages as $usage)
                <tr>
                    <td>{{ $usage->product->name }} ({{ $usage->product->catalog_number }})</td>
                    <td>{{ $usage->quantity }}</td>
                    <td>{{ $usage->product->stock }}</td>
                    <td>{{ $usage->product->purchase_price_netto }}</td>
                    <td>{{ $usage->product->sale_price_netto }}</td>
                    <td>
                        <!-- Przycisk zwiększania ilości -->
                        <form action="{{ route('tasks.increaseMaterial', [$task->id, $usage->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Zwiększ ilość</button>
                        </form>

                        <!-- Przycisk zmniejszania ilości -->
                        <form action="{{ route('tasks.decreaseMaterial', [$task->id, $usage->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Zmniejsz ilość</button>
                        </form>

                        <!-- Przycisk usunięcia materiału -->
                        <form action="{{ route('tasks.removeMaterial', [$task->id, $usage->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten materiał?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Usuń materiał</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($task->files->isNotEmpty())
            <h5>Pliki:</h5>
            <ul>
                @foreach($task->files as $file)
                    <li><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a></li>
                @endforeach
            </ul>
        @endif
        
    </div>
</div>

<!-- Formularz dodawania materiału -->
<div class="card mt-4">
    <div class="card-header">Dodaj materiał do realizacji</div>
    <div class="card-body">
        <form action="{{ route('tasks.addMaterial', $task->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Materiał</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->catalog_number }}) | ({{ $product->stock }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Ilość</label>
                <input type="number" name="quantity" id="quantity" class="form-control" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Dodaj materiał</button>
        </form>
    </div>

    <!-- Obsługa błędów -->
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

@endsection
