<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CapacityType;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CapacityTypeController extends Controller
{
    /**
     * Muestra la lista de tipos de aforo / zonas configuradas.
     */
    public function index(): View
    {
        $capacityTypes = CapacityType::orderBy('id', 'asc')->get();
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        return view('web.capacity_types', compact('capacityTypes', 'settings', 'organizer'));
    }

    /**
     * Registra un nuevo tipo de aforo en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:capacity_types,name',
            'color_hex' => 'required|string|max:20',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        CapacityType::create([
            'name' => strtoupper($validated['name']),
            'color_hex' => $validated['color_hex'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', '¡Tipo de aforo registrado exitosamente!');
    }

    /**
     * Actualiza la información de un tipo de aforo existente.
     */
    public function update(Request $request, CapacityType $capacityType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:capacity_types,name,' . $capacityType->id,
            'color_hex' => 'required|string|max:20',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        $capacityType->update([
            'name' => strtoupper($validated['name']),
            'color_hex' => $validated['color_hex'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', '¡Tipo de aforo actualizado correctamente!');
    }

    /**
     * Elimina un tipo de aforo de la base de datos.
     */
    public function destroy(CapacityType $capacityType): RedirectResponse
    {
        $capacityType->delete();

        return redirect()->back()->with('success', '¡Tipo de aforo eliminado del sistema!');
    }
}
