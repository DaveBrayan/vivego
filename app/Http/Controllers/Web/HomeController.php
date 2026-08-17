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

        // Eventos de muestra si la base de datos tiene pocos eventos iniciales
        $demoEvents = [
            [
                'id' => 901,
                'title' => 'Reggaeton Fest 2026 – Estadio Nacional',
                'slug' => 'reggaeton-fest-2026',
                'badge' => 'ÚLTIMOS CUPOS',
                'badge_color' => 'badge-red',
                'date' => 'SÁB, 20 AGO • 20:00 HRS',
                'grid_date' => '20 AGO - 20:00 PM',
                'day' => '20',
                'month' => 'AGO',
                'time' => '20:00 HRS',
                'venue' => 'Estadio Nacional, Lima',
                'price' => '120.00',
                'category' => 'CONCIERTOS',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
                'sold_percent' => '94%',
                'company_name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.',
            ],
            [
                'id' => 902,
                'title' => 'Cusco Electronic Night – Sacsayhuamán',
                'slug' => 'cusco-electronic-night-2026',
                'badge' => 'PREVENTA 1',
                'badge_color' => 'badge-orange',
                'date' => 'VIE, 26 AGO • 21:30 HRS',
                'grid_date' => '26 AGO - 21:30 PM',
                'day' => '26',
                'month' => 'AGO',
                'time' => '21:30 HRS',
                'venue' => 'Explanada Sacsayhuamán, Cusco',
                'price' => '85.00',
                'category' => 'FIESTAS',
                'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1200&q=80',
                'sold_percent' => '82%',
                'company_name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            ],
            [
                'id' => 903,
                'title' => 'Festival Valles Verdes – Arequipa',
                'slug' => 'festival-valles-verdes-arequipa',
                'badge' => 'DESTACADO',
                'badge_color' => 'badge-red',
                'date' => 'DOM, 28 AGO • 16:00 HRS',
                'grid_date' => '28 AGO - 16:00 PM',
                'day' => '28',
                'month' => 'AGO',
                'time' => '16:00 HRS',
                'venue' => 'Jardines del Misti, Arequipa',
                'price' => '65.00',
                'category' => 'FESTIVALES',
                'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80',
                'sold_percent' => '88%',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
            ],
            [
                'id' => 904,
                'title' => 'Indie Sunset Session – Costa Verde',
                'slug' => 'indie-sunset-session',
                'badge' => 'PREVENTA 2',
                'badge_color' => 'badge-orange',
                'date' => 'MAR, 22 AGO • 17:00 HRS',
                'grid_date' => '22 AGO - 17:00 PM',
                'day' => '22',
                'month' => 'AGO',
                'time' => '17:00 HRS',
                'venue' => 'Playa Agua Dulce, Chorrillos',
                'price' => '55.00',
                'category' => 'FESTIVALES',
                'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
                'sold_percent' => '78%',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
            ],
            [
                'id' => 905,
                'title' => 'Stand Up Comedy: Risas de Noche',
                'slug' => 'stand-up-comedy-risas-de-noche',
                'badge' => 'HOY',
                'badge_color' => 'badge-dark',
                'date' => 'DOM, 27 AGO • 20:30 HRS',
                'grid_date' => '27 AGO - 20:30 PM',
                'day' => '27',
                'month' => 'AGO',
                'time' => '20:30 HRS',
                'venue' => 'Teatro Canout, Miraflores',
                'price' => '40.00',
                'category' => 'TEATRO',
                'image' => 'https://images.unsplash.com/photo-1585699324551-f6c309eedeca?auto=format&fit=crop&w=800&q=80',
                'sold_percent' => '96%',
                'company_name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            ],
            [
                'id' => 906,
                'title' => 'Rock en la Playa 2026 – Costa Verde',
                'slug' => 'rock-en-la-playa-2026',
                'badge' => 'EXCLUSIVO',
                'badge_color' => 'badge-orange',
                'date' => 'SÁB, 02 SEP • 18:00 HRS',
                'grid_date' => '02 SEP - 18:00 PM',
                'day' => '02',
                'month' => 'SEP',
                'time' => '18:00 HRS',
                'venue' => 'Arena 1, San Miguel',
                'price' => '95.00',
                'category' => 'CONCIERTOS',
                'image' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=800&q=80',
                'sold_percent' => '88%',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
            ],
            [
                'id' => 907,
                'title' => 'Perú Sabor – Festival Gastronómico',
                'slug' => 'peru-sabor-gastronomico',
                'badge' => 'GOURMET',
                'badge_color' => 'badge-orange',
                'date' => 'MAR, 05 SEP • 12:00 HRS',
                'grid_date' => '05 SEP - 12:00 PM',
                'day' => '05',
                'month' => 'SEP',
                'time' => '12:00 HRS',
                'venue' => 'Parque de la Exposición, Lima',
                'price' => '45.00',
                'category' => 'GASTRONOMÍA',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
                'sold_percent' => '92%',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
            ],
            [
                'id' => 908,
                'title' => 'Clásico del Fútbol – Final Nacional',
                'slug' => 'clasico-futbol-final-nacional',
                'badge' => 'DEPORTES',
                'badge_color' => 'badge-red',
                'date' => 'DOM, 10 SEP • 15:30 HRS',
                'grid_date' => '10 SEP - 15:30 PM',
                'day' => '10',
                'month' => 'SEP',
                'time' => '15:30 HRS',
                'venue' => 'Estadio Monumental, Ate',
                'price' => '75.00',
                'category' => 'DEPORTES',
                'image' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80',
                'sold_percent' => '98%',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
            ],
        ];

        // Combinar eventos de la BD al inicio para que siempre tengan prioridad máxima
        $allEvents = array_merge($formattedDbEvents, $demoEvents);

        // Hero Events (hasta 4 en el carrusel principal)
        $heroEvents = array_slice($allEvents, 0, 3);

        // Side Cards Events (2 tarjetas destacadas laterales en el Hero)
        $sideEvents = array_slice($allEvents, 1, 2);
        if (count($sideEvents) < 2) {
            $sideEvents = array_slice($allEvents, 0, 2);
        }

        // Próximos Eventos Masivos (Todos los eventos de la BD + complementarios para grid completo)
        $events = $allEvents;

        $organizers = Company::all();

        return view('web.home', compact('heroEvents', 'sideEvents', 'events', 'organizers'));
    }
}
