<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Vive Go');
            $table->text('site_description')->nullable();
            $table->string('logo_dark')->default('images/logo.png');
            $table->string('logo_white')->default('images/logo-white.png');
            $table->string('favicon')->default('images/loading.png');
            $table->string('primary_color')->default('#FF5500');
            $table->string('secondary_color')->default('#FF1E3C');
            $table->string('timezone')->default('America/Lima');
            $table->string('currency')->default('PEN');
            $table->string('currency_symbol')->default('S/');
            $table->timestamps();
        });

        // Insert default system settings record
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            'site_name' => 'Vive Go',
            'site_description' => 'Plataforma integral de ticketing, venta de entradas masivas, conciertos, teatro y festivales en Perú.',
            'logo_dark' => 'images/logo.png',
            'logo_white' => 'images/logo-white.png',
            'favicon' => 'images/loading.png',
            'primary_color' => '#FF5500',
            'secondary_color' => '#FF1E3C',
            'timezone' => 'America/Lima',
            'currency' => 'PEN',
            'currency_symbol' => 'S/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
