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
            if (!Schema::hasColumn('ticket_sales', 'status')) {
                $table->string('status', 30)->default('completed')->after('seller_name')->index();
            }
            if (!Schema::hasColumn('ticket_sales', 'is_upgrade')) {
                $table->boolean('is_upgrade')->default(false)->after('status')->index();
            }
            if (!Schema::hasColumn('ticket_sales', 'upgraded_from_sale_id')) {
                $table->unsignedBigInteger('upgraded_from_sale_id')->nullable()->after('is_upgrade')->index();
            }
            if (!Schema::hasColumn('ticket_sales', 'upgraded_to_sale_id')) {
                $table->unsignedBigInteger('upgraded_to_sale_id')->nullable()->after('upgraded_from_sale_id')->index();
            }
            if (!Schema::hasColumn('ticket_sales', 'upgrade_difference')) {
                $table->decimal('upgrade_difference', 10, 2)->default(0.00)->after('upgraded_to_sale_id');
            }
            if (!Schema::hasColumn('ticket_sales', 'upgrade_original_zone')) {
                $table->string('upgrade_original_zone', 100)->nullable()->after('upgrade_difference');
            }
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('event_tickets', 'status')) {
                $table->string('status', 30)->default('valid')->after('source')->index();
            }
            if (!Schema::hasColumn('event_tickets', 'upgraded_to_ticket_id')) {
                $table->unsignedBigInteger('upgraded_to_ticket_id')->nullable()->after('status')->index();
            }
            if (!Schema::hasColumn('event_tickets', 'upgraded_at')) {
                $table->timestamp('upgraded_at')->nullable()->after('upgraded_to_ticket_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_sales', function (Blueprint $table) {
            $columnsToDrop = [
                'status',
                'is_upgrade',
                'upgraded_from_sale_id',
                'upgraded_to_sale_id',
                'upgrade_difference',
                'upgrade_original_zone',
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('ticket_sales', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $columnsToDrop = [
                'status',
                'upgraded_to_ticket_id',
                'upgraded_at',
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('event_tickets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
