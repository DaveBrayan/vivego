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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                           // Ej: "BLACKFRIDAY50", "VIVEGOVIP"
            $table->string('description')->nullable();                  // Descripción o notas internas
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0.00);    // Ej: 15 (%) o 25.00 (S/)
            $table->integer('usage_limit')->nullable();                 // Límite máximo de usos (null = ilimitado)
            $table->integer('used_count')->default(0);                  // Contador de usos
            $table->decimal('min_purchase_amount', 10, 2)->default(0.00);// Monto mínimo de compra requerido
            $table->dateTime('start_at');                               // Fecha y hora de inicio
            $table->dateTime('end_at');                                 // Fecha y hora de expiración
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
        Schema::dropIfExists('coupons');
    }
};
