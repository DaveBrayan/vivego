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
        Schema::create('ticket_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->index();
            $table->string('receipt_number')->unique();
            $table->string('buyer_name');
            $table->string('buyer_dni')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('zone_name');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->integer('quantity')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('Efectivo');
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->decimal('change_amount', 10, 2)->default(0.00);
            $table->json('tickets_data')->nullable();
            $table->string('seller_name')->nullable()->default('Taquilla Principal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_sales');
    }
};
