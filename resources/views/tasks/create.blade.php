@extends('layouts.app')

@section('content')
<h1>Dodaj nowe zlecenie</h1>
<br>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dodaj zlecenie</div>
            <div class="card-body">

                <!-- Wyświetlanie błędów walidacji -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
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
                        <label for="client_id">Klient</label>
                        <select name="client_id" id="client_id" class="form-control" required>
                            @foreach(App\Models\Client::all() as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->company_name ? $client->company_name : $client->first_name . ' ' . $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="in_progress">W trakcie realizacji</option>
                            <option value="completed">Zakończone</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="files">Pliki</label>
                        <input type="file" name="files[]" id="files" class="form-control" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
