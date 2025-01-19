<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MaterialUsage;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('files')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'planned_material_budget' => 'required|numeric|min:0',
            'status' => 'required|in:in_progress,completed',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $task = Task::create($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task_files', 'public');
                TaskFile::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zlecenie dodane.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'planned_material_budget' => 'required|numeric|min:0',
            'status' => 'required|in:in_progress,completed',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $task->update($validated);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task_files', 'public');
                TaskFile::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Zlecenie zaktualizowane.');
    }

    public function destroy(Task $task)
    {
        foreach ($task->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Zlecenie usunięte.');
    }

    public function show($id)
    {
        // Sprawdzanie, czy istnieje zadanie o danym ID
        $task = Task::findOrFail($id);
    
        // Pobieranie materiałów z paginacją
        $materials = $task->materialUsages()->with('product')->paginate(5);
    
        // Obliczanie aktualnych wydatków na materiały
        $current_material_expenses = $task->materialUsages->sum(function ($usage) {
            return $usage->quantity * $usage->product->purchase_price_netto;
        });
    
        // Pozostały budżet
        $remaining_budget = $task->planned_material_budget - $current_material_expenses;
    
        // Pobieranie wszystkich produktów (materiałów) do listy wyboru
        $products = Product::all();
    
        // Jeśli to żądanie AJAX, zwróć tylko tabelę
        if (request()->ajax() || request()->has('partial')) {
            return view('tasks.show', compact('task', 'materials', 'products', 'current_material_expenses', 'remaining_budget'))
                ->renderSections()['content'];
        }
    
        // Przekazanie danych do widoku
        return view('tasks.show', compact('task', 'materials', 'products', 'current_material_expenses', 'remaining_budget'));
    }

    public function addMaterialToTask(Request $request, $taskId)
    {
        // Pobranie zadania
        $task = Task::findOrFail($taskId);
        
        // Sprawdzanie, czy produkt istnieje
        $product = Product::findOrFail($request->input('product_id'));
        $quantityToAdd = $request->input('quantity');

        // Sprawdzanie dostępności materiału w magazynie
        if ($product->stock < $quantityToAdd) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brak wystarczającej ilości materiału w magazynie.'
                ]);
            }
            return redirect()->route('tasks.show', $taskId)
                            ->with('error', 'Brak wystarczającej ilości materiału w magazynie.');
        }

        try {
            // Dodanie materiału do zadania
            $task->materialUsages()->create([
                'product_id' => $product->id,
                'quantity' => $quantityToAdd,
            ]);

            // Zmniejszenie stanu magazynowego
            $product->stock -= $quantityToAdd;
            $product->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Materiał został dodany do zadania, a stan magazynu zaktualizowany.'
                ]);
            }

            // Przekierowanie z komunikatem sukcesu
            return redirect()->route('tasks.show', $taskId)
                            ->with('success', 'Materiał został dodany do zadania, a stan magazynu zaktualizowany.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wystąpił błąd podczas dodawania materiału.'
                ]);
            }
            return redirect()->route('tasks.show', $taskId)
                            ->with('error', 'Wystąpił błąd podczas dodawania materiału.');
        }
    }

    public function increaseMaterialQuantity($taskId, $materialUsageId)
    {
        $task = Task::findOrFail($taskId);
        $materialUsage = $task->materialUsages()->findOrFail($materialUsageId);
        $product = $materialUsage->product;

        // Sprawdzamy, czy jest wystarczająca ilość w magazynie
        if ($product->stock > 0) {
            // Zwiększenie ilości materiału w zadaniu
            $materialUsage->quantity += 1;
            $materialUsage->save();

            // Zmniejszenie ilości w magazynie
            $product->stock -= 1;
            $product->save();

            return redirect()->route('tasks.show', $taskId)
                            ->with('success', 'Ilość materiału została zwiększona, a magazyn zaktualizowany.');
        }

        return redirect()->route('tasks.show', $taskId)
                     ->with('error', 'Brak wystarczającej ilości materiału w magazynie.');
    }

    public function decreaseMaterialQuantity($taskId, $materialUsageId)
    {
        $task = Task::findOrFail($taskId);
        $materialUsage = $task->materialUsages()->findOrFail($materialUsageId);
        $product = $materialUsage->product;

        // Sprawdzamy, czy ilość materiału w zadaniu jest większa od 0
        if ($materialUsage->quantity > 0) {
            // Zmniejszenie ilości materiału w zadaniu
            $materialUsage->quantity -= 1;
            $materialUsage->save();

            // Zwiększenie ilości w magazynie
            $product->stock += 1;
            $product->save();

            return redirect()->route('tasks.show', $taskId)
                            ->with('success', 'Ilość materiału została zmniejszona, a magazyn zaktualizowany.');
        }

        return redirect()->route('tasks.show', $taskId)
                        ->with('error', 'Ilość materiału w zadaniu nie może być mniejsza niż 0.');
    }

    public function removeMaterial($taskId, $materialUsageId)
    {
        $task = Task::findOrFail($taskId);
        $materialUsage = $task->materialUsages()->findOrFail($materialUsageId);
        $product = $materialUsage->product;

        // Dodanie ilości materiału do magazynu
        $product->stock += $materialUsage->quantity;
        $product->save();

        // Usunięcie materiału z zadania
        $materialUsage->delete();

        return redirect()->route('tasks.show', $taskId)
                        ->with('success', 'Materiał został usunięty, a magazyn zaktualizowany.');
    }

    public function exportToPdf(Request $request, $id)
    {
        // Pobieranie danych z zadania
        $task = Task::with('materialUsages.product')->findOrFail($id);

        // Obliczanie aktualnych wydatków na materiały
        $current_material_expenses = $task->materialUsages->sum(function ($usage) {
            return $usage->quantity * $usage->product->purchase_price_netto;
        });

        // Obliczanie sum kolumn
        $total_purchase_price = $task->materialUsages->sum(function ($usage) {
            return $usage->quantity * $usage->product->purchase_price_netto;
        });

        $total_sale_price = $task->materialUsages->sum(function ($usage) {
            return $usage->quantity * $usage->product->sale_price_netto;
        });

        // Pozostały budżet
        $remaining_budget = $task->planned_material_budget - $current_material_expenses;

        // Pobieranie opcji z żądania
        $includePurchasePrice = $request->has('include_purchase_price');
        $includeSalePrice = $request->has('include_sale_price');

        // Generowanie PDF z widoku
        $pdf = Pdf::loadView('tasks.pdf', compact(
            'task',
            'current_material_expenses',
            'remaining_budget',
            'total_purchase_price',
            'total_sale_price',
            'includePurchasePrice',
            'includeSalePrice'
        ));

        // Zwracanie pliku PDF jako odpowiedź
        return $pdf->stream('zlecenie_' . $task->id . '.pdf');
    }
}
