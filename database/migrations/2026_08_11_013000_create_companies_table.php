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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('tax_id', 50); // RUC / NIT
            $table->string('email', 255)->nullable(); // Opcional
            $table->string('country_code', 10)->default('+51');
            $table->string('country_iso', 5)->default('pe');
            $table->string('phone', 30)->nullable(); // Opcional
            $table->string('address', 255)->nullable();
            $table->string('status', 20)->default('Activo');
            $table->timestamps();
        });

        // Insertar datos de prueba iniciales
        DB::table('companies')->insert([
            [
                'name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
                'tax_id' => '20601234567',
                'email' => 'contacto@acau.pe',
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '987654321',
                'address' => 'Av. Arequipa 1234, Lima - Perú',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'tax_id' => '20559876543',
                'email' => 'ventas@vivego.pe',
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '912345678',
                'address' => 'Av. Javier Prado Este 456, San Isidro, Lima',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.',
                'tax_id' => '900123456-1',
                'email' => 'info@entretenimiento.co',
                'country_code' => '+57',
                'country_iso' => 'co',
                'phone' => '3159876543',
                'address' => 'Calle 93 # 11-15, Bogotá - Colombia',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
