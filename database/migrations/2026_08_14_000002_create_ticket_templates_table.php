<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('General');
            $table->string('bg_color')->default('#FFFFFF');
            $table->string('strip_color')->default('#000000');
            $table->json('positions')->nullable();
            $table->json('elements')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Insertar plantilla general por defecto
        DB::table('ticket_templates')->insert([
            'name' => 'Plantilla Oficial Vive Go 2026',
            'category' => 'Estándar General',
            'bg_color' => '#FFFFFF',
            'strip_color' => '#000000',
            'positions' => json_encode([
                'canvaElTitle' => ['top' => '15px', 'left' => '20px', 'visible' => true],
                'canvaElZone' => ['top' => '45px', 'left' => '20px', 'visible' => true],
                'canvaElPrice' => ['top' => '15px', 'right' => '20px', 'visible' => true],
                'canvaElBanner' => ['top' => '75px', 'left' => '20px', 'visible' => true],
                'canvaElBuyer' => ['bottom' => '15px', 'left' => '20px', 'visible' => true],
                'canvaElVenue' => ['bottom' => '15px', 'right' => '20px', 'visible' => true],
                'canvaElQR' => ['top' => '0px', 'left' => '0px', 'visible' => true],
            ]),
            'elements' => json_encode([
                ['id' => 'canvaElTitle', 'name' => 'Título del Evento', 'type' => 'system'],
                ['id' => 'canvaElZone', 'name' => 'Zona / Sector', 'type' => 'system'],
                ['id' => 'canvaElPrice', 'name' => 'Precio de Entrada', 'type' => 'system'],
                ['id' => 'canvaElBanner', 'name' => 'Banner del Show', 'type' => 'system'],
                ['id' => 'canvaElBuyer', 'name' => 'Datos de Comprador', 'type' => 'system'],
                ['id' => 'canvaElVenue', 'name' => 'Recinto & Fecha', 'type' => 'system'],
                ['id' => 'canvaElQR', 'name' => 'Código QR Gigante', 'type' => 'system'],
            ]),
            'is_default' => true,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_templates');
    }
};
