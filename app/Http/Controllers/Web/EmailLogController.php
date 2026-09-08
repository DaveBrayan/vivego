<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\Event;
use App\Services\EmailLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailLogController extends Controller
{
    /**
     * Muestra la bandeja de registros de correos enviados y fallidos
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('q', ''));
        $status = $request->input('status', 'all');
        $eventId = $request->input('event_id');

        $query = EmailLog::with(['ticketSale', 'event'])->latest('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('ticketSale', function ($sq) use ($search) {
                      $sq->where('receipt_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($status === 'sent') {
            $query->where('status', 'sent');
        } elseif ($status === 'failed') {
            $query->where('status', 'failed');
        }

        if (!empty($eventId)) {
            $query->where('event_id', $eventId);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Estadísticas generales
        $totalEmails = EmailLog::count();
        $sentEmails = EmailLog::where('status', 'sent')->count();
        $failedEmails = EmailLog::where('status', 'failed')->count();
        $successRate = $totalEmails > 0 ? round(($sentEmails / $totalEmails) * 100, 1) : 100;

        $events = Event::orderBy('title', 'asc')->get(['id', 'title']);

        return view('web.email_logs', compact(
            'logs',
            'totalEmails',
            'sentEmails',
            'failedEmails',
            'successRate',
            'events',
            'search',
            'status',
            'eventId'
        ));
    }

    /**
     * Reintenta el envío de un correo fallido o emitido
     */
    public function resend(Request $request, $id)
    {
        $log = EmailLog::with(['ticketSale.event', 'ticketSale.eventTickets'])->findOrFail($id);

        $result = EmailLogService::resend($log);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 500);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Retorna los detalles técnicos y mensaje de error de un log vía JSON
     */
    public function show($id): JsonResponse
    {
        $log = EmailLog::with(['ticketSale', 'event'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'log' => [
                'id' => $log->id,
                'recipient_name' => $log->recipient_name,
                'recipient_email' => $log->recipient_email,
                'subject' => $log->subject,
                'status' => $log->status,
                'error_message' => $log->error_message,
                'details' => $log->details,
                'attempts' => $log->attempts,
                'sent_at' => $log->sent_at ? $log->sent_at->format('d/m/Y H:i:s') : null,
                'created_at' => $log->created_at->format('d/m/Y H:i:s'),
                'event_title' => $log->event?->title ?? ($log->details['event_title'] ?? 'N/A'),
                'receipt_number' => $log->ticketSale?->receipt_number ?? ($log->details['receipt_number'] ?? 'N/A'),
            ]
        ]);
    }

    /**
     * Elimina un registro de log
     */
    public function destroy($id): RedirectResponse
    {
        $log = EmailLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Registro de correo eliminado correctamente.');
    }
}
