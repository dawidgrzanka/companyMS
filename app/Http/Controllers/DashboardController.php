<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\Task;
use App\Models\Client;
use App\Models\Product;

class DashboardController extends Controller
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

        // Podstawowe podsumowania
        $totalNet = Expense::whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with('items')
            ->get()
            ->sum(function ($expense) {
                return $expense->items->sum('net_value');
            });
        $totalGross = Expense::whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->with('items')
            ->get()
            ->sum(function ($expense) {
                return $expense->items->sum('gross_value');
            });
        $totalVat = $totalGross - $totalNet;

        // Ostatnie wydatki
        $recentExpenses = Expense::latest()->limit(5)->get();

        // Liczba klientów, zadań, produktów
        $totalClients = Client::count();
        $totalTasks = Task::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();

        return view('dashboard', compact(
            'totalNet', 'totalGross', 'totalVat', 
            'recentExpenses', 'totalClients', 'totalTasks', 'lowStockProducts', 'month'
        ));
    }
}
