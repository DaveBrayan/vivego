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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number', 50)->unique()->comment('Código correlativo ej. REC-202608-0001');
            
            // 1. Identificación del Consumidor Reclamante
            $table->enum('person_type', ['natural', 'juridica'])->default('natural');
            $table->string('full_name', 255);
            $table->string('document_type', 30)->default('DNI');
            $table->string('document_number', 30);
            $table->string('email', 150);
            $table->string('phone', 30);
            $table->text('address');
            $table->string('department', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            
            // Si es menor de edad
            $table->boolean('is_minor')->default(false);
            $table->string('parent_name', 255)->nullable();
            $table->string('parent_document_type', 30)->nullable();
            $table->string('parent_document_number', 30)->nullable();
            $table->string('parent_email', 150)->nullable();
            $table->string('parent_phone', 30)->nullable();
            
            // 2. Identificación del Bien Contratado
            $table->enum('contracted_good_type', ['PRODUCTO', 'SERVICIO'])->default('SERVICIO');
            $table->decimal('claimed_amount', 12, 2)->default(0.00);
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('order_code', 100)->nullable()->comment('Código de compra o entrada si aplica');
            $table->text('good_description')->comment('Descripción del evento, entrada o servicio contratado');
            
            // 3. Detalle de la Reclamación
            $table->enum('claim_type', ['RECLAMO', 'QUEJA'])->default('RECLAMO');
            $table->text('claim_detail')->comment('Detalle de los hechos');
            $table->text('consumer_request')->comment('Pedido concreto del consumidor');
            
            // 4. Gestión y Respuesta Administrativa
            $table->enum('status', ['Pendiente', 'En Proceso', 'Atendido', 'Anulado'])->default('Pendiente');
            $table->text('admin_response')->nullable()->comment('Respuesta oficial brindada al consumidor');
            $table->timestamp('admin_response_date')->nullable();
            $table->unsignedBigInteger('admin_responder_id')->nullable();
            $table->text('admin_notes')->nullable()->comment('Notas internas del equipo administrativo');
            
            // Auditoría técnica
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Índices para optimización
            $table->index('status');
            $table->index('claim_type');
            $table->index('document_number');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
