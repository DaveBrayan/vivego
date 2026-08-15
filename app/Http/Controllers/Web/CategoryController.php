<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Muestra la lista de categorías registradas en el sistema.
     */
    public function index(): View
    {
        $categories = Category::orderBy('id', 'asc')->get();
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        return view('web.categories', compact('categories', 'settings', 'organizer'));
    }

    /**
     * Registra una nueva categoría en el sistema.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? '🎤',
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'Activo',
        ]);

        return redirect()->back()->with('success', '¡Categoría creada exitosamente!');
    }

    /**
     * Actualiza la información de una categoría existente.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? '🎤',
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'Activo',
        ]);

        return redirect()->back()->with('success', '¡Información de la categoría actualizada correctamente!');
    }

    /**
     * Elimina una categoría del sistema.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->back()->with('success', '¡La categoría ha sido eliminada correctamente!');
    }
}
