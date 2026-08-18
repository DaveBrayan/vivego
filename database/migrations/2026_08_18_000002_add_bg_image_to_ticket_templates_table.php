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
        Schema::table('ticket_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_templates', 'bg_image')) {
                $table->text('bg_image')->nullable()->after('bg_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_templates', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_templates', 'bg_image')) {
                $table->dropColumn('bg_image');
            }
        });
    }
};
