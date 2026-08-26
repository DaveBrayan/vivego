<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Muestra el panel administrativo de Cupones de Descuento.
     */
    public function index(): View
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        $events = Event::orderBy('title', 'asc')->get(['id', 'title', 'event_date', 'venue_name']);

        // Métricas rápidas
        $activeCoupons = $coupons->where('is_active', true)->count();
        $totalUses = $coupons->sum('used_count');

        return view('web.coupons', compact('coupons', 'events', 'activeCoupons', 'totalUses'));
    }

    /**
     * Guarda un nuevo cupón.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'code' => strtoupper(trim((string)$request->input('code'))),
        ]);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'usage_limit' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
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
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0.00;

        Coupon::create($validated);

        return redirect()->route('web.coupons')->with('success', '¡Cupón de descuento creado exitosamente!');
    }

    /**
     * Actualiza un cupón existente.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $request->merge([
            'code' => strtoupper(trim((string)$request->input('code'))),
        ]);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'usage_limit' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
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
        $validated['min_purchase_amount'] = $validated['min_purchase_amount'] ?? 0.00;

        if ($validated['scope'] === 'all_events') {
            $validated['event_ids'] = null;
        } else {
            $validated['excluded_event_ids'] = null;
        }

        $coupon->update($validated);

        return redirect()->route('web.coupons')->with('success', '¡Cupón actualizado correctamente!');
    }

    /**
     * Alterna el estado activo/inactivo vía AJAX.
     */
    public function toggleStatus(Coupon $coupon): JsonResponse
    {
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => $coupon->is_active ? 'Cupón activado.' : 'Cupón pausado/desactivado.'
        ]);
    }

    /**
     * Elimina un cupón.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('web.coupons')->with('success', 'Cupón eliminado correctamente.');
    }
}
