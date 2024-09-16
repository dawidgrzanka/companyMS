<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Wyświetlanie listy produktów
    public function index(Request $request)
    {
        $query = Product::query();

        // Wyszukiwanie
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Sortowanie
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            // Domyślne sortowanie
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(10);

        return view('products.index', compact('products'));
    }

    // Wyświetlanie formularza do dodania nowego produktu
    public function create()
    {
        return view('products.create');
    }

    // Przechowywanie nowego produktu w bazie danych
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'purchase_price_netto' => 'required|numeric',
            'purchase_price_brutto' => 'required|numeric',
            'sale_price_netto' => 'required|numeric',
            'sale_price_brutto' => 'required|numeric',
            'margin' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produkt został dodany.');
    }

    // Wyświetlanie szczegółów produktu oraz historii ruchów magazynowych
    public function show(Product $product)
    {
        $movements = $product->stockMovements()->orderBy('created_at', 'desc')->get();
        return view('products.show', compact('product', 'movements'));
    }

    // Ostrzeżenia o niskich stanach magazynowych
    public function checkLowStock()
    {
        $threshold = 10; // Przykładowy próg niskiego stanu magazynowego
        $products = Product::where('stock', '<', $threshold)->get();

        return view('products.low_stock', compact('products'));
    }

    // Formularz edycji produktu
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Aktualizacja produktu
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'purchase_price_netto' => 'required|numeric',
            'purchase_price_brutto' => 'required|numeric',
            'sale_price_netto' => 'required|numeric',
            'sale_price_brutto' => 'required|numeric',
            'margin' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produkt został zaktualizowany.');
    }

    // Usuwanie produktu
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produkt został usunięty.');
    }
}
