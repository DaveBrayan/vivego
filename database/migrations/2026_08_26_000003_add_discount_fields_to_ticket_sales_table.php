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
            if (!Schema::hasColumn('ticket_sales', 'original_subtotal')) {
                $table->decimal('original_subtotal', 10, 2)->nullable()->after('unit_price');
            }
            if (!Schema::hasColumn('ticket_sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0.00)->after('original_subtotal');
            }
            if (!Schema::hasColumn('ticket_sales', 'discount_description')) {
                $table->string('discount_description')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('ticket_sales', 'campaign_name')) {
                $table->string('campaign_name')->nullable()->after('discount_description');
            }
            if (!Schema::hasColumn('ticket_sales', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('campaign_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_sales', function (Blueprint $table) {
            $table->dropColumn([
                'original_subtotal',
                'discount_amount',
                'discount_description',
                'campaign_name',
                'coupon_code'
            ]);
        });
    }
};
