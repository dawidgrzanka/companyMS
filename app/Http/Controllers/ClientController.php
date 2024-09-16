<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Wyświetlenie listy klientów
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    // Formularz do stworzenia nowego klienta
    public function create()
    {
        return view('clients.create');
    }

    // Przechowanie nowego klienta w bazie danych
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'nip' => 'nullable|string|unique:clients|max:15',
        ]);

        $client = Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Klient został dodany.');
    }

    // Wyświetlenie szczegółów klienta
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    // Formularz do edycji klienta
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Aktualizacja danych klienta
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'nip' => 'nullable|string|unique:clients,nip,' . $client->id,
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Dane klienta zostały zaktualizowane.');
    }

    // Usunięcie klienta
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Klient został usunięty.');
    }
}
