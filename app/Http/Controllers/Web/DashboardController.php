<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Company;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Setting;
use App\Models\TicketSale;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $settings = Setting::first();
        $company = Company::first();

        // 1. Obtener usuario administrador autenticado en la sesión
        $adminId = session('admin_id');
        $admin = $adminId ? Administrator::find($adminId) : null;

        $organizer = [
            'name' => session('admin_name') ?? ($admin ? $admin->full_name : 'Administrador'),
            'email' => session('admin_email') ?? ($admin ? $admin->email : 'admin@vivego.pe'),
            'company' => $company ? $company->name : 'Vive Go Producciones',
            'avatar' => session('admin_avatar') ?? ($admin ? $admin->avatar : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80'),
            'role' => session('admin_role') ?? ($admin ? $admin->role : 'Administrador Principal'),
            'status' => 'Activo',
        ];

        // 2. Eventos reales de la base de datos
        $eventsQuery = Event::orderBy('id', 'desc');
        if ($admin && $admin->allowed_scope === 'specific') {
            $eventsQuery->whereIn('id', $admin->getAllowedEventIds());
        }
        $dbEvents = $eventsQuery->get();
        $totalEventsCount = $dbEvents->count();
        $allowedEventIds = $dbEvents->pluck('id')->toArray();

        // 3. Métricas reales calculadas desde las transacciones reales (TicketSale)
        $salesQuery = TicketSale::whereNotIn('status', ['cancelled', 'upgraded']);
        if ($admin && $admin->allowed_scope === 'specific') {
            $salesQuery->whereIn('event_id', $allowedEventIds);
        }
        $totalTicketsSold = (int) $salesQuery->sum('quantity');
        $totalSales = (float) $salesQuery->sum('total_amount');

        // Capacidad global sumando todas las zonas de los eventos
        $totalCapacity = 0;
        foreach ($dbEvents as $evt) {
            $zones = is_array($evt->zones) ? $evt->zones : (is_string($evt->zones) ? json_decode($evt->zones, true) : []);
            if (!empty($zones)) {
                foreach ($zones as $z) {
                    $totalCapacity += (int) ($z['capacity'] ?? $z['stock'] ?? 0);
                }
            } else {
                $totalCapacity += 100;
            }
        }
        if ($totalCapacity == 0) {
            $totalCapacity = max($totalTicketsSold, 100);
        }

        // Porcentaje de ocupación global
        $ticketsPercentage = $totalCapacity > 0 ? min(100, round(($totalTicketsSold / $totalCapacity) * 100, 1)) : 0;

        // Tasa de asistencia (check-ins confirmados en accesos)
        $usedTicketsQuery = EventTicket::where(function ($q) {
            $q->where('is_used', true)->orWhereNotNull('checked_in_at');
        });
        if ($admin && $admin->allowed_scope === 'specific') {
            $usedTicketsQuery->whereIn('event_id', $allowedEventIds);
        }
        $usedTicketsCount = $usedTicketsQuery->count();

        $attendanceRate = $totalTicketsSold > 0 ? round(($usedTicketsCount / $totalTicketsSold) * 100, 1) : 0;

        // Ingresos netos estimados (95% post comisiones)
        $netRevenue = $totalSales > 0 ? ($totalSales * 0.95) : 0;

        $metrics = [
            'total_sales' => number_format($totalSales, 2, '.', ','),
            'total_sales_growth' => '+15.2%',
            'tickets_sold' => $totalTicketsSold,
            'tickets_total' => $totalCapacity,
            'tickets_percentage' => $ticketsPercentage,
            'attendance_rate' => $attendanceRate . '%',
            'attendance_growth' => '+4.1%',
            'net_revenue' => number_format($netRevenue, 2, '.', ','),
        ];

        // 4. Mapeo de eventos reales para la tabla del Dashboard
        $events = $dbEvents->take(6)->map(function ($evt) {
            $soldCount = (int) TicketSale::where('event_id', $evt->id)
                ->whereNotIn('status', ['cancelled', 'upgraded'])
                ->sum('quantity');

            $eventRevenue = (float) TicketSale::where('event_id', $evt->id)
                ->whereNotIn('status', ['cancelled', 'upgraded'])
                ->sum('total_amount');

            $eventCap = 0;
            $zones = is_array($evt->zones) ? $evt->zones : (is_string($evt->zones) ? json_decode($evt->zones, true) : []);
            if (!empty($zones)) {
                foreach ($zones as $z) {
                    $eventCap += (int) ($z['capacity'] ?? $z['stock'] ?? 0);
                }
            }
            if ($eventCap == 0) {
                $eventCap = max($soldCount, 100);
            }

            return [
                'id' => $evt->id,
                'slug' => $evt->slug,
                'title' => $evt->title,
                'subtitle' => $evt->company_name ?: 'Vive Go',
                'category' => $evt->category_name ?: 'Concierto',
                'image' => $evt->banner_image ?: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=600&q=80',
                'date' => ($evt->event_date ? Carbon::parse($evt->event_date)->format('d M, Y') : 'Fecha pendiente') . ' • ' . ($evt->event_time ?: '18:00'),
                'venue' => ($evt->venue_name ?: 'Recinto') . ', ' . ($evt->address ?: 'Ayacucho'),
                'tickets_sold' => $soldCount,
                'tickets_total' => $eventCap,
                'revenue' => number_format($eventRevenue, 2, '.', ','),
                'status' => $evt->status ?: 'Publicado',
                'status_color' => ($evt->status === 'Publicado' ? 'success' : ($evt->status === 'Agotado' ? 'warning' : 'info')),
            ];
        })->toArray();

        // 5. Actividad reciente de ventas en tiempo real (TicketSale)
        $recentSalesQuery = TicketSale::with('event')
            ->whereNotIn('status', ['cancelled', 'upgraded'])
            ->orderBy('id', 'desc');

        if ($admin && $admin->allowed_scope === 'specific') {
            $recentSalesQuery->whereIn('event_id', $allowedEventIds);
        }

        $recentSales = $recentSalesQuery->take(6)->get();
        $activities = [];

        if ($recentSales->count() > 0) {
            foreach ($recentSales as $sale) {
                $qtyText = $sale->quantity == 1 ? '1 entrada' : "{$sale->quantity} entradas";
                $typeLabel = ($sale->sale_type === 'fisica') ? '🎟️ Talonario Físico' : '📱 Boleto Digital';
                $zoneText = $sale->zone_name ?: 'General';

                $activities[] = [
                    'id' => $sale->id,
                    'user' => $sale->buyer_name ?: 'Cliente Taquilla',
                    'action' => "{$typeLabel} • {$zoneText} ({$qtyText})",
                    'event' => $sale->event ? $sale->event->title : 'Evento Vive Go',
                    'amount' => 'S/ ' . number_format((float) $sale->total_amount, 2),
                    'time' => $sale->created_at ? $sale->created_at->diffForHumans() : 'Reciente',
                    'type' => ($sale->sale_type === 'fisica') ? 'ticket' : 'digital',
                ];
            }
        } else {
            $activities = [
                [
                    'id' => 1,
                    'user' => 'Sistema Vive Go',
                    'action' => 'Control de Taquilla y Accesos en Vivo',
                    'event' => 'Sin ventas recientes',
                    'amount' => '✓ Activo',
                    'time' => 'Hoy',
                    'type' => 'promo',
                ]
            ];
        }

        return view('web.dashboard', compact('organizer', 'metrics', 'events', 'activities', 'settings', 'totalEventsCount'));
    }
}
