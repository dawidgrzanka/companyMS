<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    // Wyświetlanie formularza do rejestrowania ruchów magazynowych
    public function create(Product $product)
    {
        return view('stock_movements.create', compact('product'));
    }

    // Rejestrowanie ruchu magazynowego
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        // Aktualizacja stanu magazynowego
        if ($request->movement_type == 'in') {
            $product->stock += $request->quantity;
        } else {
            if ($product->stock < $request->quantity) {
                return redirect()->back()->withErrors('Nie można zdjąć więcej produktów niż dostępnych na stanie.');
            }
            $product->stock -= $request->quantity;
        }

        $product->save();

        // Rejestrowanie ruchu magazynowego
        StockMovement::create([
            'product_id' => $request->product_id,
            'movement_type' => $request->movement_type,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('products.show', $product->id)->with('success', 'Ruch magazynowy został zarejestrowany.');
    }

    // Wyświetlanie historii ruchów magazynowych (dla konkretnego produktu)
    public function history(Product $product)
    {
        $movements = $product->stockMovements()->orderBy('created_at', 'desc')->get();
        return view('stock_movements.history', compact('product', 'movements'));
    }
}
