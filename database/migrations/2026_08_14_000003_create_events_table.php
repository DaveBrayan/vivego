<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category_name')->nullable();
            $table->string('company_name')->nullable();
            $table->longText('banner_image')->nullable();
            $table->date('event_date')->nullable();
            $table->string('event_time')->nullable();
            $table->string('venue_name')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->json('zones')->nullable();
            $table->string('status')->default('Publicado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
