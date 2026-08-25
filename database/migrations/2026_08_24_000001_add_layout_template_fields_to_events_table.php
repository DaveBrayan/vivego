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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'layout_template')) {
                $table->string('layout_template')->default('template_1')->after('sales_type');
            }
            if (!Schema::hasColumn('events', 'background_image')) {
                $table->longText('background_image')->nullable()->after('layout_template');
            }
            if (!Schema::hasColumn('events', 'artist_image')) {
                $table->longText('artist_image')->nullable()->after('background_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'artist_image')) {
                $table->dropColumn('artist_image');
            }
            if (Schema::hasColumn('events', 'background_image')) {
                $table->dropColumn('background_image');
            }
            if (Schema::hasColumn('events', 'layout_template')) {
                $table->dropColumn('layout_template');
            }
        });
    }
};
