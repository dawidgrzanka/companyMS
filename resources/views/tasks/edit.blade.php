@extends('layouts.app')

@section('content')
<h1>Edytuj zlecenie</h1>
<br>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edytuj zlecenie</div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name">Nazwa</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Opis</label>
                        <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="client_id">Klient</label>
                        <select name="client_id" id="client_id" class="form-control" required>
                            @foreach(App\Models\Client::all() as $client)
                                <option value="{{ $client->id }}" {{ $task->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>W trakcie realizacji</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Zakończone</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="files">Pliki</label>
                        <input type="file" name="files[]" id="files" class="form-control" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Zaktualizuj</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
