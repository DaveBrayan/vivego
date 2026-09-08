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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_sale_id')->nullable()->index();
            $table->unsignedBigInteger('event_id')->nullable()->index();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->index();
            $table->string('subject')->nullable();
            $table->string('mail_type')->default('ticket_purchase'); // ticket_purchase, ticket_resend, courtesy, etc.
            $table->string('status')->default('sent')->index(); // sent, failed
            $table->text('error_message')->nullable();
            $table->longText('details')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('ticket_sale_id')->references('id')->on('ticket_sales')->onDelete('set null');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
