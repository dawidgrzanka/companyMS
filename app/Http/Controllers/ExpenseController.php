<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('items')->orderBy('issue_date', 'desc')->paginate(20);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
         // Zatrzyma działanie i pokaże dane formularza
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
            'items.*.vat_rate' => 'required|numeric|in:0,5,8,23',
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
                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'net_price' => $item['net_price'],
                    'vat_rate' => $item['vat_rate'],
                    'net_value' => round($item['quantity'] * $item['net_price'], 2),
                    'gross_value' => round($item['quantity'] * $item['net_price'] * (1 + ($item['vat_rate'] / 100)), 2),
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

}
