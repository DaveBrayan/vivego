<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class EventDetailController extends Controller
{
    /**
     * Muestra la vista detallada de un evento público para compra de entradas.
     */
    public function show(string $slug): View
    {
        $monthsEs = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];
        $daysEs = [
            0 => 'Dom', 1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb'
        ];

        // Buscar evento en la base de datos por slug o por ID
        $eventModel = Event::where('slug', $slug)->orWhere('id', $slug)->first();

        if (!$eventModel) {
            // Buscar por coincidencia parcial de slug si tiene sufijo numérico
            $eventModel = Event::where('slug', 'LIKE', '%' . $slug . '%')->first();
        }

        if ($eventModel) {
            // Formatear Fecha y Hora
            $formattedDate = "Sáb 15 Nov, '26";
            $rawDate = $eventModel->event_date;
            $rawTime = $eventModel->event_time ?: '20:00 HRS';

            if (!empty($rawDate)) {
                try {
                    $dt = Carbon::parse($rawDate);
                    $dayName = $daysEs[(int)$dt->format('w')] ?? 'Sáb';
                    $dayNum = $dt->format('d');
                    $monthName = $monthsEs[(int)$dt->format('m')] ?? 'Ago';
                    $yearShort = substr($dt->format('Y'), -2);
                    $formattedDate = "{$dayName} {$dayNum} {$monthName}, '{$yearShort}";
                } catch (\Throwable $e) {
                    $formattedDate = (string) $rawDate;
                }
            }

            // Normalizar hora
            $timeDisplay = $rawTime;
            if (!str_contains(strtoupper($timeDisplay), 'HRS') && !str_contains(strtoupper($timeDisplay), 'PM') && !str_contains(strtoupper($timeDisplay), 'AM')) {
                $timeDisplay .= ' HRS';
            }

            // Zonas y Tipos de Entrada
            $zones = is_array($eventModel->zones) ? $eventModel->zones : (is_string($eventModel->zones) ? json_decode($eventModel->zones, true) : []);
            $tickets = [];

            if (!empty($zones)) {
                foreach ($zones as $idx => $zone) {
                    $zoneName = $zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1));
                    $priceVal = isset($zone['price']) ? (float)$zone['price'] : 50.00;
                    $capacityVal = isset($zone['capacity']) ? (int)$zone['capacity'] : 100;

                    $tickets[] = [
                        'id' => $idx + 1,
                        'name' => $zoneName,
                        'price' => number_format($priceVal, 2, '.', ''),
                        'capacity' => $capacityVal,
                        'available' => true,
                    ];
                }
            } else {
                $tickets = [
                    ['id' => 1, 'name' => 'General Preventa', 'price' => '50.00', 'capacity' => 200, 'available' => true],
                    ['id' => 2, 'name' => 'VIP Preferencial', 'price' => '90.00', 'capacity' => 100, 'available' => true],
                ];
            }

            // Detalles y Descripción
            $details = [];
            if (!empty($eventModel->description)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($eventModel->description));
                foreach ($lines as $line) {
                    $trimmed = trim($line);
                    if (!empty($trimmed)) {
                        $details[] = $trimmed;
                    }
                }
            }

            if (count($details) === 0) {
                $details[] = "Gran espectáculo en vivo: {$eventModel->title}. Vive una experiencia musical y cultural inigualable con sonido de alta fidelidad, efectos visuales y la mejor puesta en escena.";
            }

            // Párrafos complementarios de seguridad y logística
            $details[] = 'Llega con anticipación. Te recomendamos estar en el recinto al menos 45 minutos antes del inicio del evento para un ingreso ágil y seguro.';
            $details[] = 'Presenta tu boleto digital con código QR en tu smartphone o tu entrada física emitida en los puntos de control autorizados.';
            $details[] = 'Conserva tu entrada en buen estado. Prohibido el reingreso una vez validado el acceso en puerta.';

            // Compañía Organizadora
            $company = null;
            if (!empty($eventModel->company_name)) {
                $company = Company::where('name', $eventModel->company_name)->first();
            }
            if (!$company) {
                $company = Company::first();
            }

            $organizerName = $eventModel->company_name ?: ($company ? $company->name : 'PRODUCCIONES VIVE GO S.A.C.');
            $organizerRuc = $company ? ($company->tax_id ?: '20601234567') : '20601234567';

            // Ubicación y Recinto
            $venueName = $eventModel->venue_name ?: 'Recinto Oficial';
            $venueAddress = $eventModel->address ?: 'Dirección Oficial del Evento';
            $mapQuery = urlencode($venueName . ', ' . $venueAddress);
            $mapEmbed = "https://maps.google.com/maps?q={$mapQuery}&t=&z=15&ie=UTF8&iwloc=&output=embed";

            // Etiquetas (Tags)
            $tags = is_array($eventModel->tags) ? $eventModel->tags : (is_string($eventModel->tags) ? json_decode($eventModel->tags, true) : []);
            if (empty($tags)) {
                $tags = [
                    Str::slug($eventModel->category_name ?: 'evento'),
                    'envivo',
                    'concierto',
                    'entradas',
                    'vivego'
                ];
            }

            // Imagen Banner Poster
            $bannerImage = $eventModel->banner_image ?: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1600&q=80';

            // Ciudad
            $city = 'Perú';
            if (!empty($eventModel->address)) {
                $parts = explode('-', $eventModel->address);
                if (count($parts) > 1) {
                    $city = trim(end($parts));
                } else {
                    $cityParts = explode(',', $eventModel->address);
                    $city = trim(end($cityParts));
                }
            }

            $event = [
                'id' => $eventModel->id,
                'title' => $eventModel->title,
                'subtitle' => ($eventModel->category_name ?? 'Concierto Oficial') . ' • Organizado por ' . $organizerName,
                'slug' => $eventModel->slug ?: $slug,
                'category' => $eventModel->category_name ?: 'Conciertos',
                'city' => $city,
                'advisory' => 'Apto para todo público',
                'banner_image' => $bannerImage,
                'reference_image' => $eventModel->reference_image,
                'layout_template' => $eventModel->layout_template ?? 'template_1',
                'background_image' => $eventModel->background_image,
                'artist_image' => $eventModel->artist_image,
                'dates' => [
                    ['id' => 1, 'date' => $formattedDate, 'time' => $timeDisplay, 'selected' => true],
                ],
                'tickets' => $tickets,
                'venue' => [
                    'name' => $venueName,
                    'address' => $venueAddress,
                    'latitude' => $eventModel->latitude,
                    'longitude' => $eventModel->longitude,
                    'map_embed' => $mapEmbed,
                ],
                'details' => $details,
                'organizer' => [
                    'name' => $organizerName,
                    'ruc' => $organizerRuc,
                    'email' => $company?->email ?: 'contacto@vivego.pe',
                ],
                'tags' => $tags,
            ];

            $izipay = \App\Models\PaymentGateway::getIzipay();

            return view('web.event_detail', compact('event', 'izipay'));
        }

        // Si no existe en la base de datos (fallback para slugs de demostración)
        $demoEvents = [
            'reggaeton-fest-2026' => [
                'title' => 'Reggaeton Fest 2026 – Estadio Nacional',
                'category' => 'Conciertos',
                'city' => 'Lima',
                'banner_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1600&q=80',
                'venue_name' => 'Estadio Nacional',
                'venue_address' => 'José Díaz s/n, Cercado de Lima',
                'price_vip' => '180.00',
                'price_gen' => '120.00',
                'organizer_name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.',
                'organizer_ruc' => '20559876543',
                'tags' => ['reggaeton', 'urbano', 'lima', 'estadio', 'concierto', 'vivego'],
            ],
            'cusco-electronic-night-2026' => [
                'title' => 'Cusco Electronic Night – Sacsayhuamán',
                'category' => 'Fiestas & Electrónica',
                'city' => 'Cusco',
                'banner_image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1600&q=80',
                'venue_name' => 'Explanada Sacsayhuamán',
                'venue_address' => 'Sacsayhuamán, Cusco',
                'price_vip' => '140.00',
                'price_gen' => '85.00',
                'organizer_name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
                'organizer_ruc' => '20601234567',
                'tags' => ['electronica', 'cusco', 'sacsayhuaman', 'dj', 'rave', 'vivego'],
            ],
            'festival-valles-verdes-arequipa' => [
                'title' => 'Festival Valles Verdes – Arequipa',
                'category' => 'Festivales',
                'city' => 'Arequipa',
                'banner_image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1600&q=80',
                'venue_name' => 'Jardines del Misti',
                'venue_address' => 'Av. Bolognesi 402, Yanahuara, Arequipa',
                'price_vip' => '110.00',
                'price_gen' => '65.00',
                'organizer_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'organizer_ruc' => '20609876543',
                'tags' => ['festival', 'arequipa', 'musica', 'cultura', 'misti', 'vivego'],
            ],
        ];

        $demo = $demoEvents[$slug] ?? null;
        $title = $demo ? $demo['title'] : ucwords(str_replace('-', ' ', $slug));
        $category = $demo ? $demo['category'] : 'Eventos Especiales';
        $city = $demo ? $demo['city'] : 'Lima, Perú';
        $bannerImg = $demo ? $demo['banner_image'] : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1600&q=80';
        $venueName = $demo ? $demo['venue_name'] : 'Recinto Principal Vive Go';
        $venueAddress = $demo ? $demo['venue_address'] : 'Av. Javier Prado Este 4200, Lima';
        $organizerName = $demo ? $demo['organizer_name'] : 'PRODUCCIONES VIVE GO S.A.C.';
        $organizerRuc = $demo ? $demo['organizer_ruc'] : '20601234567';
        $tags = $demo ? $demo['tags'] : ['evento', 'envivo', 'entradas', 'oficial', 'vivego'];

        $event = [
            'title' => $title,
            'subtitle' => 'Entradas oficiales con nominación digital instantánea',
            'slug' => $slug,
            'category' => $category,
            'city' => $city,
            'advisory' => 'Apto para público en general',
            'banner_image' => $bannerImg,
            'dates' => [
                ['id' => 1, 'date' => "Sáb 20 Ago, '26", 'time' => '08:00 PM', 'selected' => true],
                ['id' => 2, 'date' => "Dom 21 Ago, '26", 'time' => '06:00 PM', 'selected' => false],
            ],
            'tickets' => [
                ['id' => 101, 'name' => 'General Preferencial', 'price' => ($demo ? $demo['price_gen'] : '60.00'), 'capacity' => 300, 'available' => true],
                ['id' => 102, 'name' => 'VIP Platinum Box', 'price' => ($demo ? $demo['price_vip'] : '120.00'), 'capacity' => 100, 'available' => true],
            ],
            'venue' => [
                'name' => $venueName,
                'address' => $venueAddress,
                'map_embed' => 'https://maps.google.com/maps?q=' . urlencode($venueName . ', ' . $venueAddress) . '&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ],
            'details' => [
                "Disfruta de {$title}. Una producción de primer nivel con montaje audiovisual de última generación y los artistas más destacados.",
                'Llega con anticipación. Te recomendamos estar en el recinto al menos 45 minutos antes del inicio de la función para el control de acceso.',
                'Presenta tu boleto digital con código QR en tu smartphone o tu entrada física autorizada para validación en puerta.',
                'Prohibido el ingreso con alimentos, bebidas o elementos punzocortantes. El recinto cuenta con estacionamiento y seguridad privada.',
            ],
            'organizer' => [
                'name' => $organizerName,
                'ruc' => $organizerRuc,
            ],
            'tags' => $tags,
        ];

        $izipay = \App\Models\PaymentGateway::getIzipay();

        return view('web.event_detail', compact('event', 'izipay'));
    }
}
