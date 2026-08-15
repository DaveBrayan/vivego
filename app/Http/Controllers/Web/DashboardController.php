<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizer = [
            'name' => 'Christian Gómez',
            'responsible' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        $metrics = [
            'total_sales' => '48,950.00',
            'total_sales_growth' => '+18.4%',
            'tickets_sold' => 1420,
            'tickets_total' => 1800,
            'tickets_percentage' => 78.8,
            'attendance_rate' => '94.2%',
            'attendance_growth' => '+3.1%',
            'net_revenue' => '41,607.50',
        ];

        $events = [
            [
                'id' => 1,
                'title' => 'Mezcla 2026',
                'subtitle' => 'Misma familia, nueva generación',
                'category' => 'Arte & Cultura',
                'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=600&q=80',
                'date' => "08 Ago, 2026 • 08:00 PM",
                'venue' => 'Teatro de Lucía, Miraflores',
                'tickets_sold' => 450,
                'tickets_total' => 500,
                'revenue' => '36,000.00',
                'status' => 'En Venta',
                'status_color' => 'success',
            ],
            [
                'id' => 2,
                'title' => 'Festival Electro Nights',
                'subtitle' => 'Edición Verano 2026',
                'category' => 'Conciertos',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=600&q=80',
                'date' => "15 Ago, 2026 • 10:00 PM",
                'venue' => 'Arena 1, San Miguel',
                'tickets_sold' => 820,
                'tickets_total' => 1000,
                'revenue' => '98,400.00',
                'status' => 'Agotando',
                'status_color' => 'warning',
            ],
            [
                'id' => 3,
                'title' => 'Noche de Jazz & Wine',
                'subtitle' => 'Experiencia acústica intima',
                'category' => 'Música Live',
                'image' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?auto=format&fit=crop&w=600&q=80',
                'date' => "22 Ago, 2026 • 07:30 PM",
                'venue' => 'Barranco Social Club',
                'tickets_sold' => 150,
                'tickets_total' => 300,
                'revenue' => '14,550.00',
                'status' => 'Próximo',
                'status_color' => 'info',
            ]
        ];

        $activities = [
            [
                'id' => 1,
                'user' => 'Mariana Torres',
                'action' => 'Compró 2x Entradas General',
                'event' => 'Mezcla 2026',
                'amount' => '+S/ 160.00',
                'time' => 'Hace 3 min',
                'type' => 'ticket',
            ],
            [
                'id' => 2,
                'user' => 'Carlos Mendoza',
                'action' => 'Compró 1x VIP Preferencial',
                'event' => 'Festival Electro Nights',
                'amount' => '+S/ 120.00',
                'time' => 'Hace 12 min',
                'type' => 'ticket',
            ],
            [
                'id' => 3,
                'user' => 'Sistema Vive Go',
                'action' => 'Código VIVEGO20 aplicado',
                'event' => 'Noche de Jazz',
                'amount' => 'Descuento 20%',
                'time' => 'Hace 45 min',
                'type' => 'promo',
            ],
            [
                'id' => 4,
                'user' => 'Taquilla Digital',
                'action' => 'Liquidación parcial procesada',
                'event' => 'Mezcla 2026',
                'amount' => 'S/ 12,400.00',
                'time' => 'Hace 2 horas',
                'type' => 'payout',
            ]
        ];

        return view('web.dashboard', compact('organizer', 'metrics', 'events', 'activities'));
    }
}
