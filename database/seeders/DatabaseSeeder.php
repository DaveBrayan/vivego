<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketSale;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar todos los eventos que no sean "Chúpate la Plata con Son del Duke en Ayacucho"
        Event::where('title', 'not like', '%Chúpate la Plata%')->delete();

        $zonesConfig = [
            ['capacity_type' => 'Aforo VIP', 'name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
            ['capacity_type' => 'Aforo Preferencial', 'name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
            ['capacity_type' => 'Aforo General', 'name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
        ];

        // Si no existe, crearlo con datos completos; si ya existe, actualizar sus zonas a 60
        $event = Event::where('title', 'like', '%Chúpate la Plata%')->first();
        if (!$event) {
            $event = Event::create([
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
                'zones' => $zonesConfig,
                'status' => 'Publicado',
            ]);
        } else {
            $event->update([
                'zones' => $zonesConfig,
            ]);
        }

        // Asegurar que las plantillas oficiales existan
        \App\Models\TicketTemplate::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Plantilla 1: Taquilla Clásica Oficial 2026',
                'category' => 'Estructura 1: Logo Izquierda',
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

        // Eliminar ventas huérfanas
        TicketSale::whereNotIn('event_id', Event::pluck('id'))->delete();
    }
}
