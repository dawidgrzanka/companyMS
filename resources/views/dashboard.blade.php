@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Pulpit startowy</h2>

    <div class="row">
        <!-- LEWA STRONA - KARTY Z SUMAMI -->
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Suma wydatków Netto</h5>
                    <p class="card-text fs-4">{{ number_format($totalNet, 2) }} zł</p>
                </div>
            </div>

            <div class="card bg-danger text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Suma wydatków Brutto</h5>
                    <p class="card-text fs-4">{{ number_format($totalGross, 2) }} zł</p>
                </div>
            </div>

            <div class="card bg-warning text-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Suma VAT wydatków</h5>
                    <p class="card-text fs-4">{{ number_format($totalVat, 2) }} zł</p>
                </div>
            </div>

            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Statystyki</h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-users"></i> Klienci: <strong>{{ $totalClients }}</strong></li>
                        <li><i class="fas fa-tasks"></i> Inwestycje: <strong>{{ $totalTasks }}</strong></li>
                        <li><i class="fas fa-box"></i> Niski stan magazynowy: <strong>{{ $lowStockProducts }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- PRAWA STRONA - TABELA WYDATKÓW -->
        <div class="col-md-8">
            <h4><i class="fas fa-receipt"></i> Ostatnie wydatki w miesiącu <b>{{ \Carbon\Carbon::createFromDate(null, $month, 1)->locale('pl')->monthName }}</b></h4>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Numer</th>
                            <th>Data</th>
                            <th>Kwota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentExpenses as $expense)
                        <tr>
                            <td>{{ $expense->number }}</td>
                            <td>{{ $expense->issue_date->format('d.m.Y') }}</td>
                            <td>{{ number_format($expense->total_gross, 2) }} zł</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
