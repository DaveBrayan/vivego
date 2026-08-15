<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class EventDetailController extends Controller
{
    public function show(string $slug): View
    {
        $event = [
            'title' => 'Mezcla',
            'subtitle' => 'Misma familia, nueva generación',
            'slug' => $slug,
            'category' => 'Arte & Cultura',
            'city' => 'Lima',
            'advisory' => 'Apto para público en general',
            'banner_image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1600&q=80',
            'dates' => [
                ['id' => 1, 'date' => "Vie 07 Ago, '26", 'time' => '08:00 PM', 'selected' => false],
                ['id' => 2, 'date' => "Sáb 08 Ago, '26", 'time' => '08:00 PM', 'selected' => true],
                ['id' => 3, 'date' => "Dom 09 Ago, '26", 'time' => '06:00 PM', 'selected' => false],
            ],
            'tickets' => [
                ['id' => 101, 'name' => 'General', 'price' => '80.00', 'available' => true],
                ['id' => 102, 'name' => 'VIP Preferencial', 'price' => '120.00', 'available' => true],
            ],
            'venue' => [
                'name' => 'Teatro de Lucía',
                'address' => 'Cl. Bellavista 512, Miraflores, Lima',
                'map_embed' => 'https://maps.google.com/maps?q=Cl.+Bellavista+512,+Miraflores,+Lima&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ],
            'details' => [
                'Llega con anticipación. Te recomendamos estar en el teatro al menos 30 minutos antes del inicio de la función.',
                'Una vez iniciada la función, no se permitirá el ingreso a la sala. Te agradecemos tomar las previsiones necesarias y evitar insistir con el ingreso una vez comenzada la obra.',
                'Ten en cuenta que no hay muchas opciones de estacionamiento cercanas al teatro. Recomendamos, de ser posible, utilizar taxi o aplicaciones de transporte.',
                'Si vienes en vehículo particular, considera el tráfico y el tiempo necesario para encontrar estacionamiento antes de dirigirte al teatro.',
            ],
            'organizer' => [
                'name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
                'ruc' => '20506112487',
            ],
            'tags' => [
                'teatro', 'Adultos', 'Drama', 'abusos', 'Blackbird',
                'consecuencias permanentes', 'heridas', 'relación ilegal',
                'confrontación', 'Culpa', 'Deseo'
            ]
        ];

        return view('web.event_detail', compact('event'));
    }
}
