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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 20)->default('🎤');
            $table->string('description', 255)->nullable();
            $table->string('status', 20)->default('Activo');
            $table->timestamps();
        });

        // Insertar categorías iniciales
        DB::table('categories')->insert([
            [
                'name' => 'Conciertos',
                'slug' => 'conciertos',
                'icon' => '🎤',
                'description' => 'Espectáculos musicales en vivo, recitales y presentaciones de bandas',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Festivales',
                'slug' => 'festivales',
                'icon' => '🎪',
                'description' => 'Festivales masivos de música, entretenimiento y experiencia multifase',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teatro & Cultura',
                'slug' => 'teatro-cultura',
                'icon' => '🎭',
                'description' => 'Obras teatrales, musicales, musicales y comedia en vivo',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conferencias & Tech',
                'slug' => 'conferencias-tech',
                'icon' => '💻',
                'description' => 'Congresos, charlas corporativas, summits de innovación y workshops',
                'status' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deportes & Fitness',
                'slug' => 'deportes-fitness',
                'icon' => '⚽',
                'description' => 'Partidos de fútbol, maratones, carreras y exhibiciones deportivas',
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
        Schema::dropIfExists('categories');
    }
};
