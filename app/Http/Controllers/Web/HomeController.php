<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Muestra la página principal de Vive Go con los eventos reales de la base de datos.
     */
    public function index(): View
    {
        $months = [
            1 => 'ENE', 2 => 'FEB', 3 => 'MAR', 4 => 'ABR', 5 => 'MAY', 6 => 'JUN',
            7 => 'JUL', 8 => 'AGO', 9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DIC'
        ];
        $days = [
            0 => 'DOM', 1 => 'LUN', 2 => 'MAR', 3 => 'MIÉ', 4 => 'JUE', 5 => 'VIE', 6 => 'SÁB'
        ];

        // Obtener eventos guardados en la BD (los mismos de "Mis Eventos" del Admin)
        $dbEvents = Event::with('sales')->orderBy('id', 'desc')->get();

        $formattedDbEvents = [];
        $badgeOptions = ['PREVENTA OFICIAL', 'ÚLTIMOS CUPOS', 'MÁS VENDIDO', 'DESTACADO', 'EN VIVO', 'RECOMENDADO'];

        foreach ($dbEvents as $index => $ev) {
            $zones = is_array($ev->zones) ? $ev->zones : (is_string($ev->zones) ? json_decode($ev->zones, true) : []);
            $minPrice = (!empty($zones) && count($zones) > 0) ? min(array_column($zones, 'price')) : 50.00;
            $currentAvailable = !empty($zones) ? (int) array_sum(array_column($zones, 'capacity')) : 100;

            $ticketsSold = $ev->sales ? (int) $ev->sales->sum('quantity') : 0;
            $totalCapacity = $currentAvailable + $ticketsSold;
            $capacityPercentage = $totalCapacity > 0 ? min(100, round(($ticketsSold / $totalCapacity) * 100)) : 10;
            if ($capacityPercentage < 10) {
                // Simulación visual atractiva si recién se crea
                $capacityPercentage = 75 + (($ev->id * 7) % 20);
            }

            // Formatear Fecha y Hora
            $dayNum = '15';
            $monthName = 'AGO';
            $dayName = 'SÁB';
            $timeFormatted = $ev->event_time ? (str_contains(strtoupper($ev->event_time), 'HRS') || str_contains(strtoupper($ev->event_time), 'PM') || str_contains(strtoupper($ev->event_time), 'AM') ? $ev->event_time : $ev->event_time . ' HRS') : '20:00 HRS';

            if (!empty($ev->event_date)) {
                try {
                    $dt = Carbon::parse($ev->event_date);
                    $dayNum = $dt->format('d');
                    $monthName = $months[(int)$dt->format('m')] ?? 'AGO';
                    $dayName = $days[(int)$dt->format('w')] ?? 'SÁB';
                } catch (\Throwable $e) {
                    $dayNum = '15';
                    $monthName = 'AGO';
                }
            }

            $badge = $badgeOptions[$index % count($badgeOptions)];
            $badgeColor = ($index % 3 === 0) ? 'badge-red' : (($index % 3 === 1) ? 'badge-orange' : 'badge-dark');

            $image = $ev->banner_image ?: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1200&q=80';
            $venue = $ev->venue_name ?: ($ev->address ?: 'Recinto Oficial');

            $formattedDbEvents[] = [
                'id' => $ev->id,
                'title' => $ev->title,
                'slug' => $ev->slug ?: (Str::slug($ev->title) . '-' . $ev->id),
                'badge' => $badge,
                'badge_color' => $badgeColor,
                'date' => "{$dayName}, {$dayNum} {$monthName} • {$timeFormatted}",
                'grid_date' => "{$dayNum} {$monthName} - {$timeFormatted}",
                'day' => $dayNum,
                'month' => $monthName,
                'time' => $timeFormatted,
                'venue' => $venue,
                'price' => number_format((float)$minPrice, 2, '.', ''),
                'category' => mb_strtoupper($ev->category_name ?: 'CONCIERTOS'),
                'image' => $image,
                'sold_percent' => $capacityPercentage . '%',
                'company_name' => $ev->company_name ?: 'PRODUCCIONES VIVE GO S.A.C.',
            ];
        }

        // Utilizar únicamente los eventos reales creados en la base de datos
        $allEvents = $formattedDbEvents;

        // Hero Events (para el carrusel principal)
        $heroEvents = count($allEvents) > 0 ? array_slice($allEvents, 0, 4) : [];

        // Tarjetas laterales en el Hero
        $sideEvents = count($allEvents) > 1 ? array_slice($allEvents, 1, 2) : $allEvents;

        // Lista general de eventos
        $events = $allEvents;

        $organizers = Company::all();

        return view('web.home', compact('heroEvents', 'sideEvents', 'events', 'organizers'));
    }
}
