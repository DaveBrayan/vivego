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
            if (!Schema::hasColumn('ticket_templates', 'type')) {
                $table->string('type')->default('fisica')->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_templates', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_templates', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
