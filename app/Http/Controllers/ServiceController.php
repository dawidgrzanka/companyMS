<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Wyświetlenie listy usług
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    // Wyświetlenie formularza do stworzenia nowej usługi
    public function create()
    {
        return view('services.create');
    }

    // Przechowanie nowej usługi w bazie danych
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_net' => 'required|numeric',
            'price_gross' => 'required|numeric',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Usługa dodana.');
    }

    // Wyświetlenie formularza edytowania usługi
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    // Aktualizacja istniejącej usługi
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_net' => 'required|numeric',
            'price_gross' => 'required|numeric',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Usługa zaktualizowana.');
    }

    // Usunięcie usługi
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Usługa usunięta.');
    }
}
