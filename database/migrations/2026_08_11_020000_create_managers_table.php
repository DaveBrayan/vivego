<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->string('country_code', 10)->default('+51');
            $table->string('country_iso', 5)->default('pe');
            $table->string('phone', 20);
            $table->string('status', 20)->default('Activo');
            $table->timestamps();
        });

        // Insertar datos de prueba iniciales
        $acauId = DB::table('companies')->where('tax_id', '20601234567')->value('id') ?? 1;
        $vivegoId = DB::table('companies')->where('tax_id', '20559876543')->value('id') ?? 2;

        DB::table('managers')->insert([
            [
                'company_id' => $acauId,
                'first_name' => 'Christian',
                'last_name' => 'Gómez',
                'username' => 'christian.gomez',
                'email' => 'christian.gomez@vivego.pe',
                'password' => Hash::make('VG123!PASS'),
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '912345678',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $vivegoId,
                'first_name' => 'David',
                'last_name' => 'Chipana',
                'username' => 'david.chipana',
                'email' => 'david.chipana@vivego.pe',
                'password' => Hash::make('VG456!PASS'),
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '987654321',
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
        Schema::dropIfExists('managers');
    }
};
