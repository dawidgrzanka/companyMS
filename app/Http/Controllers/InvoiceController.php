<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\Item;
use PDF; // Ensure you have the PDF facade

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $availableItems = Item::all();
        return view('invoices.create', compact('availableItems'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($request->only(['title', 'status', 'notes', 'vat_rate']));
        foreach ($request->items as $item) {
            $invoiceItem = new InvoiceItem($item);
            if (!empty($item['id'])) {
                $dbItem = Item::find($item['id']);
                $invoiceItem->description = $dbItem->description;
                $invoiceItem->price = $dbItem->price;
            } else {
                $invoiceItem->description = $item['description'];
            }
            $invoice->items()->save($invoiceItem);
        }
        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $availableItems = Item::all();
        return view('invoices.edit', compact('invoice', 'availableItems'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->only(['title', 'status', 'notes', 'vat_rate']));
        $invoice->items()->delete();
        foreach ($request->items as $item) {
            $invoiceItem = new InvoiceItem($item);
            if (!empty($item['id'])) {
                $dbItem = Item::find($item['id']);
                $invoiceItem->description = $dbItem->description;
                $invoiceItem->price = $dbItem->price;
            } else {
                $invoiceItem->description = $item['description'];
            }
            $invoice->items()->save($invoiceItem);
        }
        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index');
    }

    public function exportToPDF(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->number . '.pdf');
    }
}
