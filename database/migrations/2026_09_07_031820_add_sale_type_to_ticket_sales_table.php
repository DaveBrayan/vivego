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
        Schema::table('ticket_sales', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_sales', 'sale_type')) {
                $table->string('sale_type', 30)->default('digital')->after('payment_method');
            }
        });

        // Asegurar que todas las ventas existentes queden marcadas como 'digital'
        \Illuminate\Support\Facades\DB::table('ticket_sales')
            ->whereNull('sale_type')
            ->orWhere('sale_type', '')
            ->update(['sale_type' => 'digital']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_sales', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_sales', 'sale_type')) {
                $table->dropColumn('sale_type');
            }
        });
    }
};
