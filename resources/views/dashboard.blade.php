@extends('layouts.app')

@section('content')
<h1>Panel Administracyjny</h1>
<br>

<div class="row">
    <!-- Moduł Faktur -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Faktury</h5>
            </div>
            <div class="card-body">
                <p>Przeglądaj i zarządzaj fakturami.</p>

                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj fakturami</a>
            </div>
        </div>
    </div>

    <!-- Moduł Ofert -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Oferty</h5>
            </div>
            <div class="card-body">
                <p>Twórz i zarządzaj ofertami oraz kosztorysami.</p>
                <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj ofertami</a>
            </div>
        </div>
    </div>

    <!-- Moduł Magazynu -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Magazyn</h5>
            </div>
            <div class="card-body">
                <p>Zarządzaj stanami magazynowymi materiałów.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj magazynem</a>
            </div>
        </div>
    </div>

    <!-- Moduł Klientów -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Klienci</h5>
            </div>
            <div class="card-body">
                <p>Przeglądaj i zarządzaj klientami.</p>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj klientami</a>
            </div>
        </div>
    </div>

    <!-- Moduł Usług -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Usługi</h5>
            </div>
            <div class="card-body">
                <p>Twórz i zarządzaj usługami.</p>
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj usługami</a>
            </div>
        </div>
    </div>

    <!-- Moduł Realizacji -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Realizacje</h5>
            </div>
            <div class="card-body">
                <p>Monitoruj postęp realizacji zleceń.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj realizacjami</a>
            </div>
        </div>
    </div>

    <!-- Moduł Biurko -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Biurko</h5>
            </div>
            <div class="card-body">
                <p>Zarządzaj kalendarzem, zaplanowanymi pracami i notatkami.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-2">Zarządzaj biurkiem</a>
            </div>
        </div>
    </div>

    <!-- Moduł Czasu Pracy -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Czas pracy</h5>
            </div>
            <div class="card-body">
                <p>Śledź i zarządzaj czasem pracy pracowników.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">Zarządzaj czasem pracy</a>
            </div>
        </div>
    </div>
</div>

@endsection
