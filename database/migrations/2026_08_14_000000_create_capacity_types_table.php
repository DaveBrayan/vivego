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
        Schema::dropIfExists('capacity_types');

        Schema::create('capacity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('color_hex', 20)->default('#FF5500');
            $table->string('status', 20)->default('Activo');
            $table->timestamps();
        });

        // Insertar los tipos de aforo iniciales
        DB::table('capacity_types')->insert([
            [
                'name' => 'BOX PLATINUM COMPLETO',
                'color_hex' => '#EC4899', // Rosa
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BOX LATERAL COMPLETO',
                'color_hex' => '#2563EB', // Azul
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BOX PLATINUM INDIVIDUAL',
                'color_hex' => '#EAB308', // Dorado / Amarillo
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BOX LATERAL INDIVIDUAL',
                'color_hex' => '#06B6D4', // Celeste / Cyan
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ZONA VIP STAND UP',
                'color_hex' => '#A855F7', // Púrpura / Morado
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ZONA GENERAL',
                'color_hex' => '#10B981', // Verde Menta / Menta
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
        Schema::dropIfExists('capacity_types');
    }
};
