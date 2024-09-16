<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function create(Product $product)
    {
        return view('stockMovements.create', ['product' => $product]);
    }

    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'movement_type' => 'required|in:in,out',
        'quantity' => 'required|numeric|min:1',
        'remarks' => 'nullable|string',
    ]);

    // Pobierz produkt
    $product = Product::find($request->product_id);

    // Sprawdź typ ruchu i zaktualizuj ilość
    if ($request->movement_type === 'in') {
        $product->stock += $request->quantity;
    } elseif ($request->movement_type === 'out') {
        // Upewnij się, że ilość nie spadnie poniżej zera
        if ($product->stock < $request->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'Nie ma wystarczającej ilości produktu w magazynie.']);
        }
        $product->stock -= $request->quantity;
    }

    // Zapisz zmiany w produkcie
    $product->save();

    // Zapisz ruch magazynowy
    StockMovement::create([
        'product_id' => $request->product_id,
        'movement_type' => $request->movement_type,
        'quantity' => $request->quantity,
        'remarks' => $request->remarks,
    ]);

    return redirect()->route('products.index')->with('success', 'Ruch magazynowy dodany pomyślnie.');
}

    public function history(Product $product)
    {
        $product->load('stockMovements');
        return view('stockMovements.history', compact('product'));
    }
}
