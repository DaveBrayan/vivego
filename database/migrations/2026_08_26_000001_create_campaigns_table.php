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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                     // Ej: "Black Friday 2026", "Cyber Days"
            $table->string('badge_text')->nullable();                   // Ej: "🔥 BLACK FRIDAY 30% OFF"
            $table->string('banner_color')->default('#FF5500');         // Color hex del badge/cintillo
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0.00);    // Ej: 20 (%) o 15.00 (S/)
            $table->dateTime('start_at');                               // Fecha y hora de apertura
            $table->dateTime('end_at');                                 // Fecha y hora de cierre
            $table->boolean('is_active')->default(true);
            $table->enum('scope', ['all_events', 'selected_events'])->default('all_events');
            $table->json('event_ids')->nullable();                      // IDs de eventos seleccionados
            $table->json('excluded_event_ids')->nullable();             // IDs de eventos excluidos si scope == all_events
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
