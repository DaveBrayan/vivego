<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketSale;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $eventsData = [
            [
                'id' => 1,
                'title' => 'Chúpate la Plata con Son del Duke en Ayacucho',
                'slug' => 'chupate-la-plata-con-son-del-duke-ayacucho',
                'category_name' => 'Conciertos',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'banner_image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80',
                'event_date' => '2025-04-10',
                'event_time' => '18:00',
                'venue_name' => 'Complejo San Luis',
                'address' => 'Av. Cusco 528 - AYACUCHO',
                'latitude' => -13.1631,
                'longitude' => -74.2236,
                'description' => 'Gran concierto en vivo en Ayacucho con Son del Duke.',
                'tags' => ['cumbia', 'ayacucho', 'sondelduke'],
                'template_id' => 1,
                'sales_type' => 'fisica',
                'zones' => [
                    ['capacity_type' => 'Aforo VIP', 'name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
                    ['capacity_type' => 'Aforo Preferencial', 'name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
                    ['capacity_type' => 'Aforo General', 'name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
                ],
                'status' => 'Publicado',
            ],
            [
                'id' => 2,
                'title' => 'Reggaeton Fest 2026 – Estadio Nacional',
                'slug' => 'reggaeton-fest-2026-estadio-nacional',
                'category_name' => 'Festivales',
                'company_name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.',
                'banner_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-08-20',
                'event_time' => '20:00',
                'venue_name' => 'Estadio Nacional',
                'address' => 'Calle José Díaz s/n - Lima',
                'latitude' => -12.0673,
                'longitude' => -77.0336,
                'description' => 'El festival de reggaeton y música urbana más grande del año.',
                'tags' => ['reggaeton', 'urbano', 'lima', 'festivales'],
                'template_id' => 4,
                'sales_type' => 'virtual',
                'zones' => [
                    ['capacity_type' => 'Aforo VIP', 'name' => 'ZONA PLATINUM ALL ACCESS', 'price' => 250.00, 'capacity' => 50],
                    ['capacity_type' => 'Aforo Preferencial', 'name' => 'ZONA VIP STAND UP', 'price' => 150.00, 'capacity' => 100],
                    ['capacity_type' => 'Aforo General', 'name' => 'ZONA GENERAL TRIBUNA', 'price' => 85.00, 'capacity' => 150]
                ],
                'status' => 'Publicado',
            ],
            [
                'id' => 3,
                'title' => 'Cusco Electronic Night – Sacsayhuamán',
                'slug' => 'cusco-electronic-night-sacsayhuaman',
                'category_name' => 'Festivales',
                'company_name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
                'banner_image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-08-26',
                'event_time' => '21:30',
                'venue_name' => 'Explanada Sacsayhuamán',
                'address' => 'Sacsayhuamán - Cusco',
                'latitude' => -13.5085,
                'longitude' => -71.9818,
                'description' => 'Experiencia audiovisual y música electrónica en vivo bajo las estrellas de Cusco.',
                'tags' => ['electronic', 'edm', 'cusco', 'party'],
                'template_id' => 5,
                'sales_type' => 'virtual',
                'zones' => [
                    ['capacity_type' => 'Aforo VIP', 'name' => 'VIP CYBER EXPERIENCE', 'price' => 180.00, 'capacity' => 40],
                    ['capacity_type' => 'Aforo General', 'name' => 'GENERAL ELECTRONIC DANCE', 'price' => 85.00, 'capacity' => 120]
                ],
                'status' => 'Publicado',
            ],
            [
                'id' => 4,
                'title' => 'Festival Valles Verdes – Arequipa',
                'slug' => 'festival-valles-verdes-arequipa',
                'category_name' => 'Festivales',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'banner_image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-08-28',
                'event_time' => '16:00',
                'venue_name' => 'Jardines del Misti',
                'address' => 'Av. Bolognesi 120 - Arequipa',
                'latitude' => -16.4090,
                'longitude' => -71.5375,
                'description' => 'Música, gastronomía y cultura en los valles verdes de Arequipa.',
                'tags' => ['rock', 'folclore', 'arequipa', 'gastronomia'],
                'template_id' => 2,
                'sales_type' => 'fisica',
                'zones' => [
                    ['capacity_type' => 'Aforo Preferencial', 'name' => 'ZONA PREFERENCIAL JARDINES', 'price' => 95.00, 'capacity' => 60],
                    ['capacity_type' => 'Aforo General', 'name' => 'ZONA CAMPO GENERAL', 'price' => 45.00, 'capacity' => 150]
                ],
                'status' => 'Publicado',
            ],
            [
                'id' => 5,
                'title' => 'Stand Up Comedy: Risas de Noche',
                'slug' => 'stand-up-comedy-risas-de-noche',
                'category_name' => 'Teatro & Cultura',
                'company_name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
                'banner_image' => 'https://images.unsplash.com/photo-1585699324551-f6c309eedeca?auto=format&fit=crop&w=800&q=80',
                'event_date' => '2026-08-27',
                'event_time' => '20:30',
                'venue_name' => 'Teatro Canout',
                'address' => 'Av. Petit Thouars 4550 - Miraflores, Lima',
                'latitude' => -12.1158,
                'longitude' => -77.0289,
                'description' => 'Una noche de humor y los mejores comediantes del país.',
                'tags' => ['comedia', 'standup', 'teatro', 'lima'],
                'template_id' => 3,
                'sales_type' => 'fisica',
                'zones' => [
                    ['capacity_type' => 'Aforo VIP', 'name' => 'PLATEA BAJA VIP', 'price' => 70.00, 'capacity' => 30],
                    ['capacity_type' => 'Aforo General', 'name' => 'PLATEA ALTA GENERAL', 'price' => 40.00, 'capacity' => 50]
                ],
                'status' => 'Publicado',
            ]
        ];

        foreach ($eventsData as $item) {
            $eventId = $item['id'];
            unset($item['id']);
            Event::updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }

        // Asegurar que las plantillas oficiales existan
        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Plantilla 1: Taquilla Clásica Oficial 2026',
                'category' => 'Estructura 1: Logo Izquierda',
                'type' => 'fisica',
                'bg_color' => '#FFFFFF',
                'strip_color' => '#000000',
                'positions' => [
                    'canvaElTitle' => ['top' => '15px', 'left' => '20px'],
                    'canvaElZone' => ['top' => '45px', 'left' => '20px'],
                    'canvaElPrice' => ['top' => '15px', 'left' => '430px'],
                    'canvaElBanner' => ['top' => '75px', 'left' => '20px'],
                    'canvaElBuyer' => ['top' => '220px', 'left' => '20px'],
                    'canvaElVenue' => ['top' => '220px', 'left' => '340px'],
                    'canvaElTicketNumber' => ['top' => '12px', 'left' => '65px'],
                    'canvaElQR' => ['top' => '45px', 'left' => '55px'],
                    'canvaElHash' => ['top' => '195px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '15px'],
                ],
                'elements' => [],
                'is_default' => true,
                'status' => 'active',
            ]
        );

        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Plantilla 2: Franja Logo Derecho & Stub Izquierdo',
                'category' => 'Estructura 2: Logo Derecho',
                'type' => 'fisica',
                'bg_color' => '#FFFFFF',
                'strip_color' => '#06B6D4',
                'positions' => [
                    'canvaElTicketNumber' => ['top' => '12px', 'left' => '65px'],
                    'canvaElQR' => ['top' => '45px', 'left' => '55px'],
                    'canvaElHash' => ['top' => '195px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '15px'],
                    'canvaElTitle' => ['top' => '15px', 'left' => '20px'],
                    'canvaElZone' => ['top' => '45px', 'left' => '20px'],
                    'canvaElPrice' => ['top' => '15px', 'left' => '380px'],
                    'canvaElBanner' => ['top' => '75px', 'left' => '20px'],
                    'canvaElBuyer' => ['top' => '220px', 'left' => '20px'],
                    'canvaElVenue' => ['top' => '220px', 'left' => '320px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]
        );

        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Plantilla 3: Hero Banner Panorámico & QR Central',
                'category' => 'Estructura 3: Banner Panorámico',
                'type' => 'fisica',
                'bg_color' => '#1E1B4B',
                'strip_color' => '#EAB308',
                'positions' => [
                    'canvaElBanner' => ['top' => '12px', 'left' => '15px'],
                    'canvaElTitle' => ['top' => '132px', 'left' => '15px'],
                    'canvaElZone' => ['top' => '175px', 'left' => '15px'],
                    'canvaElPrice' => ['top' => '175px', 'left' => '200px'],
                    'canvaElVenue' => ['top' => '215px', 'left' => '15px'],
                    'canvaElBuyer' => ['top' => '265px', 'left' => '15px'],
                    'canvaElQR' => ['top' => '135px', 'left' => '380px'],
                    'canvaElTicketNumber' => ['top' => '25px', 'left' => '65px'],
                    'canvaElHash' => ['top' => '90px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '170px', 'left' => '15px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]
        );

        // Plantillas Virtuales (E-Tickets Móviles Digitales con Imagen de Fondo)
        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Plantilla Virtual 1: E-Ticket Dark Neon Pro 2026',
                'category' => 'Virtual: Dark Neon Pass',
                'type' => 'virtual',
                'bg_color' => '#0F172A',
                'bg_image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
                'strip_color' => '#FF5500',
                'positions' => [
                    'canvaElLogo' => ['top' => '15px', 'left' => '25px'],
                    'canvaElTitle' => ['top' => '55px', 'left' => '25px'],
                    'canvaElZone' => ['top' => '95px', 'left' => '25px'],
                    'canvaElPrice' => ['top' => '95px', 'left' => '180px'],
                    'canvaElBanner' => ['top' => '15px', 'left' => '330px'],
                    'canvaElBuyer' => ['top' => '150px', 'left' => '25px'],
                    'canvaElVenue' => ['top' => '230px', 'left' => '25px'],
                    'canvaElTicketNumber' => ['top' => '15px', 'left' => '670px'],
                    'canvaElQR' => ['top' => '55px', 'left' => '640px'],
                    'canvaElHash' => ['top' => '205px', 'left' => '660px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '620px'],
                ],
                'elements' => [],
                'is_default' => true,
                'status' => 'active',
            ]
        );

        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'Plantilla Virtual 2: Mobile Pass Cyber Glow',
                'category' => 'Virtual: Cyber Glow Wallet',
                'type' => 'virtual',
                'bg_color' => '#0B0F19',
                'bg_image' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=1200&q=80',
                'strip_color' => '#06B6D4',
                'positions' => [
                    'canvaElBanner' => ['top' => '15px', 'left' => '20px'],
                    'canvaElTitle' => ['top' => '155px', 'left' => '20px'],
                    'canvaElZone' => ['top' => '195px', 'left' => '20px'],
                    'canvaElPrice' => ['top' => '195px', 'left' => '200px'],
                    'canvaElBuyer' => ['top' => '240px', 'left' => '20px'],
                    'canvaElVenue' => ['top' => '240px', 'left' => '320px'],
                    'canvaElTicketNumber' => ['top' => '15px', 'left' => '670px'],
                    'canvaElQR' => ['top' => '55px', 'left' => '640px'],
                    'canvaElHash' => ['top' => '205px', 'left' => '660px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '620px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]
        );

        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'Plantilla Virtual 3: Entrada Digital Minimal Gold',
                'category' => 'Virtual: Minimal Gold VIP',
                'type' => 'virtual',
                'bg_color' => '#18181B',
                'bg_image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80',
                'strip_color' => '#EAB308',
                'positions' => [
                    'canvaElLogo' => ['top' => '15px', 'left' => '25px'],
                    'canvaElTitle' => ['top' => '55px', 'left' => '25px'],
                    'canvaElZone' => ['top' => '95px', 'left' => '25px'],
                    'canvaElPrice' => ['top' => '95px', 'left' => '180px'],
                    'canvaElBanner' => ['top' => '15px', 'left' => '330px'],
                    'canvaElBuyer' => ['top' => '150px', 'left' => '25px'],
                    'canvaElVenue' => ['top' => '230px', 'left' => '25px'],
                    'canvaElTicketNumber' => ['top' => '15px', 'left' => '670px'],
                    'canvaElQR' => ['top' => '55px', 'left' => '640px'],
                    'canvaElHash' => ['top' => '205px', 'left' => '660px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '620px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]
        );

        // Eliminar ventas huérfanas
        TicketSale::whereNotIn('event_id', Event::pluck('id'))->delete();
    }
}
