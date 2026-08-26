<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Muestra el panel administrativo de Campañas Promocionales.
     */
    public function index(): View
    {
        $campaigns = Campaign::orderBy('id', 'desc')->get();
        $events = Event::orderBy('title', 'asc')->get(['id', 'title', 'event_date', 'venue_name']);

        // Métricas rápidas
        $activeCount = $campaigns->filter->isCurrentlyActive()->count();
        $totalCount = $campaigns->count();

        return view('web.campaigns', compact('campaigns', 'events', 'activeCount', 'totalCount'));
    }

    /**
     * Guarda una nueva campaña.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'badge_text' => 'nullable|string|max:191',
            'banner_color' => 'nullable|string|max:30',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'sometimes|boolean',
            'scope' => 'required|in:all_events,selected_events',
            'event_ids' => 'nullable|array',
            'event_ids.*' => 'integer',
            'excluded_event_ids' => 'nullable|array',
            'excluded_event_ids.*' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : true;
        $validated['banner_color'] = $validated['banner_color'] ?: '#FF5500';
        $validated['badge_text'] = $validated['badge_text'] ?: ('🔥 ' . strtoupper($validated['name']));

        Campaign::create($validated);

        return redirect()->route('web.campaigns')->with('success', '¡Campaña comercial creada exitosamente!');
    }

    /**
     * Actualiza una campaña existente.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'badge_text' => 'nullable|string|max:191',
            'banner_color' => 'nullable|string|max:30',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'sometimes|boolean',
            'scope' => 'required|in:all_events,selected_events',
            'event_ids' => 'nullable|array',
            'event_ids.*' => 'integer',
            'excluded_event_ids' => 'nullable|array',
            'excluded_event_ids.*' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;
        $validated['banner_color'] = $validated['banner_color'] ?: '#FF5500';
        $validated['badge_text'] = $validated['badge_text'] ?: ('🔥 ' . strtoupper($validated['name']));

        if ($validated['scope'] === 'all_events') {
            $validated['event_ids'] = null;
        } else {
            $validated['excluded_event_ids'] = null;
        }

        $campaign->update($validated);

        return redirect()->route('web.campaigns')->with('success', '¡Campaña actualizada correctamente!');
    }

    /**
     * Alterna el estado activo/inactivo vía AJAX o formulario.
     */
    public function toggleStatus(Campaign $campaign): JsonResponse
    {
        $campaign->is_active = !$campaign->is_active;
        $campaign->save();

        return response()->json([
            'success' => true,
            'is_active' => $campaign->is_active,
            'is_currently_active' => $campaign->isCurrentlyActive(),
            'message' => $campaign->is_active ? 'Campaña activada.' : 'Campaña pausada/desactivada.'
        ]);
    }

    /**
     * Elimina una campaña.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('web.campaigns')->with('success', 'Campaña eliminada correctamente.');
    }
}
