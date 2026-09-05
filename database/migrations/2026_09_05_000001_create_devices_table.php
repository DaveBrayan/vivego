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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('device_uuid')->unique();
            $table->string('pairing_token')->unique();
            $table->string('api_token', 80)->nullable()->unique();
            $table->enum('status', ['pending', 'active', 'revoked'])->default('pending');
            $table->json('assigned_events')->nullable(); // IDs de eventos autorizados, null o [] = todos
            $table->string('device_model')->nullable();
            $table->string('platform')->nullable(); // android, ios, web
            $table->string('app_version')->nullable();
            $table->string('last_ip')->nullable();
            $table->unsignedInteger('scans_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('paired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
