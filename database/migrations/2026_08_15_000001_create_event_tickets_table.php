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
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_sale_id')->nullable()->constrained('ticket_sales')->nullOnDelete();
            $table->string('ticket_code', 60);
            $table->integer('ticket_number')->default(1);
            $table->string('zone_name', 100);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->string('qr_payload', 255)->index();
            $table->string('validation_hash', 50)->nullable();
            $table->string('buyer_name', 255)->default('Público General');
            $table->string('buyer_dni', 30)->default('00000000');
            $table->string('source', 30)->default('pdf_batch'); // 'pdf_batch' o 'pos_sale'
            $table->boolean('is_used')->default(false)->index();
            $table->timestamp('checked_in_at')->nullable();
            $table->string('scanned_by', 100)->nullable();
            $table->timestamps();

            $table->index(['event_id', 'qr_payload']);
            $table->index(['event_id', 'ticket_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
