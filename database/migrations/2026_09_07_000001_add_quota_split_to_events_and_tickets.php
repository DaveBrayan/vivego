<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'quota_split_settings')) {
            Schema::table('events', function (Blueprint $table) {
                $table->json('quota_split_settings')->nullable()->after('courtesy_settings');
            });
        }

        if (Schema::hasTable('event_tickets') && !Schema::hasColumn('event_tickets', 'ticket_type')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->string('ticket_type', 20)->default('fisica')->after('source')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'quota_split_settings')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('quota_split_settings');
            });
        }

        if (Schema::hasTable('event_tickets') && Schema::hasColumn('event_tickets', 'ticket_type')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->dropColumn('ticket_type');
            });
        }
    }
};
