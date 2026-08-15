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
        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country_code')->default('+51');
            $table->string('country_iso')->default('pe');
            $table->string('phone');
            $table->string('role')->default('Administrador');
            $table->string('status')->default('Activo');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // Insert initial administrators
        \Illuminate\Support\Facades\DB::table('administrators')->insert([
            [
                'first_name' => 'Christian',
                'last_name' => 'Gómez',
                'username' => 'christian.gomez',
                'email' => 'christian.gomez@vivego.pe',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '998877665',
                'role' => 'Administrador Principal',
                'status' => 'Activo',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Deivid',
                'last_name' => 'Chipana',
                'username' => 'deivid.chipana',
                'email' => 'deivid.chipana@vivego.pe',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'country_code' => '+51',
                'country_iso' => 'pe',
                'phone' => '912345678',
                'role' => 'Administrador',
                'status' => 'Activo',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Valeria',
                'last_name' => 'Mendoza',
                'username' => 'valeria.mendoza',
                'email' => 'valeria.mendoza@vivego.pe',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'country_code' => '+57',
                'country_iso' => 'co',
                'phone' => '3109876543',
                'role' => 'Administrador',
                'status' => 'Activo',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
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
        Schema::dropIfExists('administrators');
    }
};
