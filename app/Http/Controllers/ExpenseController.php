<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);  // Pobieramy rok, domyślnie bieżący
        $month = $request->get('month', now()->month);  // Pobieramy miesiąc, domyślnie bieżący

        $expenses = Expense::with('items')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->orderBy('issue_date', 'desc')
            ->paginate(20);

        // Obliczanie sum za wybrany rok i miesiąc
        $monthlyNetSum = Expense::whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with('items')
            ->get()
            ->sum(function ($expense) {
                return $expense->items->sum('net_value');
            });
        
        $monthlyVatSum = Expense::whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with('items')
            ->get()
            ->sum(function ($expense) {
                return $expense->items->sum(function ($item) {
                    return $item->gross_value - $item->net_value;  // Obliczanie VAT jako różnica między brutto a netto
                });
            });

        return view('expenses.index', compact('expenses', 'monthlyNetSum', 'monthlyVatSum'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        // Zatrzymanie działania i pokazanie danych formularza
        $validatedData = $request->validate([
            'type' => 'required|string',
            'number' => 'required|string|unique:expenses,number,NULL,id,type,' . $request->type,
            'issue_date' => 'required|date',
            'issue_place' => 'required|string',
            'sale_date' => 'required|date',
            'seller_type' => 'required|string',
            'seller_name' => 'required|string',
            'seller_nip' => 'nullable|string|size:10',
            'seller_street' => 'required|string',
            'seller_postal_code' => 'required|string|regex:/^\d{2}-\d{3}$/',
            'seller_city' => 'required|string',
            'seller_bank_account' => 'nullable|string|size:26',
            'seller_bank_name' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'required|string',
            'items.*.net_price' => 'required|numeric|min:0',
            'items.*.vat_rate' => 'required|in:ZW,23,8,7,5,0,NP,Inne',
        ]);

        try {
            DB::beginTransaction();  // Rozpoczęcie transakcji

            // Tworzenie rekordu Expense
            $expense = Expense::create([
                'type' => $validatedData['type'],
                'number' => $validatedData['number'],
                'issue_date' => $validatedData['issue_date'],
                'issue_place' => $validatedData['issue_place'],
                'sale_date' => $validatedData['sale_date'],
                'seller_type' => $validatedData['seller_type'],
                'seller_name' => $validatedData['seller_name'],
                'seller_nip' => $validatedData['seller_nip'] ?? null,
                'seller_street' => $validatedData['seller_street'],
                'seller_postal_code' => $validatedData['seller_postal_code'],
                'seller_city' => $validatedData['seller_city'],
                'seller_bank_account' => $validatedData['seller_bank_account'] ?? null,
                'seller_bank_name' => $validatedData['seller_bank_name'] ?? null,
            ]);

            if (!$expense) {
                dd('Błąd zapisu wydatku!');
            }

            // Dodanie pozycji do ExpenseItem
            foreach ($validatedData['items'] as $item) {
                // Ustawienie wartości VAT
                if (in_array($item['vat_rate'], ['ZW', 'NP'])) {
                    // Dla "ZW" lub "NP" VAT ustawiamy na 0 (brak VAT)
                    $vatRate = 0;
                } else {
                    // W przeciwnym razie traktujemy vat_rate jako liczbę
                    $vatRate = (float) $item['vat_rate'];
                }
            
                // Obliczanie wartości netto
                $netValue = round($item['quantity'] * $item['net_price'], 2);
            
                // Dla stawek "ZW" i "NP" wartość brutto = wartość netto (brak VAT)
                if ($vatRate == 0) {
                    $grossValue = $netValue;
                } else {
                    // Dla innych stawek VAT obliczamy brutto
                    $grossValue = round($netValue * (1 + ($vatRate / 100)), 2);
                }

                // Tworzenie pozycji wydatku
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'net_price' => $item['net_price'],
                    'vat_rate' => (string) $item['vat_rate'],
                    'net_value' => $netValue,
                    'gross_value' => $grossValue,
                ]);
            }

            DB::commit();  // Zatwierdzenie transakcji
            return redirect()->route('expenses.index')->with('success', 'Wydatek dodany poprawnie!');
        } catch (\Exception $e) {
            DB::rollBack(); // Cofnięcie operacji w razie błędu
            \Log::error('Błąd dodawania wydatku: ' . $e->getMessage());
            return back()->with('error', 'Błąd: ' . $e->getMessage())->withInput();
        }
    }


    public function show($id)
    {
        // Znajdź wydatek po ID
        $expense = Expense::with('items')->findOrFail($id);

        // Zwróć widok z danym wydatkiem
        return view('expenses.show', compact('expense'));
    }

    public function destroy($id)
    {
        // Znajdź wydatek po ID
        $expense = Expense::findOrFail($id);

        // Usuń wydatek
        $expense->delete();

        // Przekierowanie z komunikatem o sukcesie
        return redirect()->route('expenses.index')->with('success', 'Wydatek został usunięty.');
    }

}
