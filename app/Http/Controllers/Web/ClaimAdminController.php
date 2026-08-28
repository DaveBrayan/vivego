<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaimAdminController extends Controller
{
    /**
     * Muestra el panel de administración del Libro de Reclamaciones
     */
    public function index(Request $request): View
    {
        $query = Claim::with(['event', 'adminResponder'])->latest();

        // Filtro por Estado
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtro por Tipo de Reclamación
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('claim_type', $request->type);
        }

        // Filtro por Búsqueda General
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('claim_number', 'LIKE', "%{$s}%")
                    ->orWhere('full_name', 'LIKE', "%{$s}%")
                    ->orWhere('document_number', 'LIKE', "%{$s}%")
                    ->orWhere('email', 'LIKE', "%{$s}%")
                    ->orWhere('phone', 'LIKE', "%{$s}%")
                    ->orWhere('order_code', 'LIKE', "%{$s}%");
            });
        }

        // Filtro por Rango de Fechas
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $claims = $query->get();

        // Estadísticas Generales
        $allClaims = Claim::all();
        $stats = [
            'total' => $allClaims->count(),
            'reclamos' => $allClaims->where('claim_type', 'RECLAMO')->count(),
            'quejas' => $allClaims->where('claim_type', 'QUEJA')->count(),
            'pendientes' => $allClaims->where('status', 'Pendiente')->count(),
            'en_proceso' => $allClaims->where('status', 'En Proceso')->count(),
            'atendidos' => $allClaims->where('status', 'Atendido')->count(),
            'anulados' => $allClaims->where('status', 'Anulado')->count(),
        ];

        $settings = Setting::first();

        return view('web.claims', compact('claims', 'stats', 'settings'));
    }

    /**
     * Obtiene el detalle completo en JSON de una reclamación específica
     */
    public function show(int $id): JsonResponse
    {
        $claim = Claim::with(['event', 'adminResponder'])->findOrFail($id);

        $createdFormatted = $claim->created_at ? $claim->created_at->format('d/m/Y H:i:s') : '-';
        $deadlineFormatted = $claim->legal_deadline ? $claim->legal_deadline->format('d/m/Y') : '-';
        $isOverdue = $claim->status !== 'Atendido' && $claim->legal_deadline && Carbon::now()->greaterThan($claim->legal_deadline);

        return response()->json([
            'success' => true,
            'claim' => $claim,
            'created_formatted' => $createdFormatted,
            'deadline_formatted' => $deadlineFormatted,
            'is_overdue' => $isOverdue,
            'event_title' => $claim->event ? $claim->event->title : null,
            'admin_name' => $claim->adminResponder ? $claim->adminResponder->full_name : null,
        ]);
    }

    /**
     * Registra o actualiza la respuesta oficial y estado del reclamo
     */
    public function respond(Request $request, int $id): JsonResponse
    {
        $claim = Claim::findOrFail($id);

        $request->validate([
            'admin_response' => 'required|string|max:5000',
            'status' => 'required|in:Pendiente,En Proceso,Atendido,Anulado',
            'admin_notes' => 'nullable|string|max:2000',
        ], [
            'admin_response.required' => 'Debes ingresar el texto de la respuesta oficial.',
            'status.required' => 'Selecciona el estado actualizado del reclamo.',
        ]);

        $adminId = session('admin_id');

        $claim->admin_response = trim($request->input('admin_response'));
        $claim->status = $request->input('status');
        $claim->admin_notes = $request->input('admin_notes') ? trim($request->input('admin_notes')) : $claim->admin_notes;
        $claim->admin_response_date = Carbon::now();
        if ($adminId) {
            $claim->admin_responder_id = $adminId;
        }
        $claim->save();

        return response()->json([
            'success' => true,
            'message' => "La respuesta para la Hoja de Reclamación {$claim->claim_number} fue guardada exitosamente con estado \"{$claim->status}\".",
            'claim' => $claim,
        ]);
    }

    /**
     * Actualiza rápidamente el estado de una reclamación
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $claim = Claim::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pendiente,En Proceso,Atendido,Anulado',
        ]);

        $claim->status = $request->input('status');
        $claim->save();

        return response()->json([
            'success' => true,
            'message' => "El estado de la reclamación {$claim->claim_number} se actualizó a \"{$claim->status}\".",
            'status' => $claim->status,
        ]);
    }

    /**
     * Elimina un registro de reclamación
     */
    public function destroy(int $id): JsonResponse
    {
        $claim = Claim::findOrFail($id);
        $claimNumber = $claim->claim_number;
        $claim->delete();

        return response()->json([
            'success' => true,
            'message' => "La reclamación \"{$claimNumber}\" fue eliminada correctamente.",
        ]);
    }
}
