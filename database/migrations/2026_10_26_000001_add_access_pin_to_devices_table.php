<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (!Schema::hasColumn('devices', 'access_pin')) {
                $table->string('access_pin', 10)->nullable()->index()->after('pairing_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'access_pin')) {
                $table->dropColumn('access_pin');
            }
        });
    }
};
