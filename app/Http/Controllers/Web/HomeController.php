<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroEvents = [
            [
                'id' => 1,
                'title' => 'Cusco Electronic Night – Sacsayhuamán',
                'slug' => 'cusco-electronic-night-2026',
                'badge' => 'PREVENTA 1',
                'date' => 'VIE, 26 AGO • 21:30 HRS',
                'venue' => 'Explanada Sacsayhuamán, Cusco',
                'price' => '85.00',
                'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1600&q=80',
            ],
            [
                'id' => 2,
                'title' => 'Reggaeton Fest 2026 – Estadio Nacional',
                'slug' => 'reggaeton-fest-2026',
                'badge' => 'ÚLTIMOS CUPOS',
                'date' => 'SÁB, 20 AGO • 20:00 HRS',
                'venue' => 'Estadio Nacional, Lima',
                'price' => '120.00',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1600&q=80',
            ],
            [
                'id' => 3,
                'title' => 'Festival Valles Verdes – Arequipa',
                'slug' => 'festival-valles-verdes-arequipa',
                'badge' => 'DESTACADO',
                'date' => 'DOM, 28 AGO • 16:00 HRS',
                'venue' => 'Jardines del Misti, Arequipa',
                'price' => '65.00',
                'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1600&q=80',
            ],
        ];

        $events = [
            [
                'id' => 1,
                'title' => 'Reggaeton Fest 2026 – Estadio Nacional',
                'slug' => 'reggaeton-fest-2026',
                'date' => '20 AGO - 20:00 PM',
                'venue' => 'Estadio Nacional, Lima',
                'price' => '120.00',
                'badge' => 'ÚLTIMOS CUPOS',
                'badge_color' => 'badge-red',
                'category' => 'CONCIERTOS',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 2,
                'title' => 'Indie Sunset Session – Costa Verde',
                'slug' => 'indie-sunset-session',
                'date' => '22 AGO - 17:00 PM',
                'venue' => 'Playa Agua Dulce, Chorrillos',
                'price' => '55.00',
                'badge' => 'PREVENTA 2',
                'badge_color' => 'badge-orange',
                'category' => 'FESTIVALES',
                'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 3,
                'title' => 'Cusco Electronic Night – Sacsayhuamán',
                'slug' => 'cusco-electronic-night-2026',
                'date' => '26 AGO - 21:30 PM',
                'venue' => 'Explanada Sacsayhuamán, Cusco',
                'price' => '85.00',
                'badge' => 'MÁS VENDIDO',
                'badge_color' => 'badge-red',
                'category' => 'FIESTAS',
                'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 4,
                'title' => 'Stand Up Comedy: Risas de Noche',
                'slug' => 'stand-up-comedy-risas-de-noche',
                'date' => '27 AGO - 20:30 PM',
                'venue' => 'Teatro Canout, Miraflores',
                'price' => '40.00',
                'badge' => 'HOY',
                'badge_color' => 'badge-dark',
                'category' => 'TEATRO',
                'image' => 'https://images.unsplash.com/photo-1585699324551-f6c309eedeca?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 5,
                'title' => 'Rock en la Playa 2026 – Costa Verde',
                'slug' => 'rock-en-la-playa-2026',
                'date' => '02 SEP - 18:00 PM',
                'venue' => 'Arena 1, San Miguel',
                'price' => '95.00',
                'badge' => 'EXCLUSIVO',
                'badge_color' => 'badge-orange',
                'category' => 'CONCIERTOS',
                'image' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 6,
                'title' => 'Perú Sabor – Festival Gastronómico',
                'slug' => 'peru-sabor-gastronomico',
                'date' => '05 SEP - 12:00 PM',
                'venue' => 'Parque de la Exposición, Lima',
                'price' => '45.00',
                'badge' => 'GOURMET',
                'badge_color' => 'badge-orange',
                'category' => 'GASTRONOMÍA',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 7,
                'title' => 'Clásico del Fútbol – Final Nacional',
                'slug' => 'clasico-futbol-final-nacional',
                'date' => '10 SEP - 15:30 PM',
                'venue' => 'Estadio Monumental, Ate',
                'price' => '75.00',
                'badge' => 'DEPORTES',
                'badge_color' => 'badge-red',
                'category' => 'DEPORTES',
                'image' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'id' => 8,
                'title' => 'Noche de Salsa & Orquesta Live',
                'slug' => 'noche-de-salsa-orquesta-live',
                'date' => '15 SEP - 22:00 PM',
                'venue' => 'Club Cocos, Lince',
                'price' => '50.00',
                'badge' => 'FIESTA',
                'badge_color' => 'badge-dark',
                'category' => 'FIESTAS',
                'image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $organizers = [];

        return view('web.home', compact('heroEvents', 'events', 'organizers'));
    }
}
