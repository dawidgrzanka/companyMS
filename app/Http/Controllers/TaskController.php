<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MaterialUsage;
use App\Models\Product;

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
            $task = Task::with('materialUsages.product')->findOrFail($id);

            // Obliczanie aktualnych wydatków na materiały
            $current_material_expenses = $task->materialUsages->sum(function($usage) {
                return $usage->quantity * $usage->product->purchase_price_netto;
            });

            // Pozostały budżet
            $remaining_budget = $task->planned_material_budget - $current_material_expenses;

            $products = Product::all(); // Pobieramy wszystkie dostępne materiały

            // Przekazanie danych do widoku
            return view('tasks.show', compact('task', 'products', 'current_material_expenses', 'remaining_budget'));
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
                return redirect()->route('tasks.show', $taskId)
                                ->with('error', 'Brak wystarczającej ilości materiału w magazynie.');
            }

            // Dodanie materiału do zadania
            $task->materialUsages()->create([
                'product_id' => $product->id,
                'quantity' => $quantityToAdd,
            ]);

            // Zmniejszenie stanu magazynowego
            $product->stock -= $quantityToAdd;
            $product->save();

            // Przekierowanie z komunikatem sukcesu
            return redirect()->route('tasks.show', $taskId)
                            ->with('success', 'Materiał został dodany do zadania, a stan magazynu zaktualizowany.');
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



}
