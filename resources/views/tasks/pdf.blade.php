<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Zlecenia</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
            line-height: 30px;
            border-top: 1px solid #ddd;
        }

        .page {
            page-break-after: always;
            position: relative;
        }

        .page:last-child {
            page-break-after: auto;
        }
    </style>
</head>
<body>
    <div class="content">
            <h1>Zlecenie: {{ $task->name }}</h1>
            <p><strong>Opis:</strong> {{ $task->description }}</p>
            <p><strong>Status:</strong> {{ $task->status }}</p>
            <p><strong>Budżet planowany:</strong> {{ number_format($task->planned_material_budget, 2) }} zł</p>
            <p><strong>Wydatki na materiały:</strong> {{ number_format($current_material_expenses, 2) }} zł</p>
            <p><strong>Pozostały budżet:</strong> {{ number_format($remaining_budget, 2) }} zł</p>

            <h3>Materiały zużyte:</h3>
            <table>
            <thead>
                <tr>
                    <th>Nazwa materiału</th>
                    <th>Ilość</th>
                    <th>Cena zakupu netto</th>
                    <th>Cena sprzedaży netto</th>
                    <th>Wartość netto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($task->materialUsages as $usage)
                    <tr>
                        <td>{{ $usage->product->name }}</td>
                        <td>{{ $usage->quantity }}</td>
                        <td>{{ number_format($usage->product->purchase_price_netto, 2) }} zł</td>
                        <td>{{ number_format($usage->product->sale_price_netto, 2) }} zł</td>
                        <td>{{ number_format($usage->quantity * $usage->product->purchase_price_netto, 2) }} zł</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Razem:</th>
                    <th>{{ number_format($total_purchase_price, 2) }} zł</th>
                    <th>{{ number_format($total_sale_price, 2) }} zł</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    
<div class="footer">
        <b>companyMS</b> - wersja stworzona dla <i>PolBel - Plus Dawid Grzanka</i>
</div>
</body>
</html>
