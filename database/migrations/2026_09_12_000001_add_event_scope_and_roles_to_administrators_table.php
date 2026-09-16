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
        Schema::table('administrators', function (Blueprint $table) {
            if (!Schema::hasColumn('administrators', 'allowed_scope')) {
                $table->string('allowed_scope')->default('all')->after('role'); // 'all' o 'specific'
            }
            if (!Schema::hasColumn('administrators', 'allowed_events')) {
                $table->json('allowed_events')->nullable()->after('allowed_scope');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrators', function (Blueprint $table) {
            if (Schema::hasColumn('administrators', 'allowed_events')) {
                $table->dropColumn('allowed_events');
            }
            if (Schema::hasColumn('administrators', 'allowed_scope')) {
                $table->dropColumn('allowed_scope');
            }
        });
    }
};
