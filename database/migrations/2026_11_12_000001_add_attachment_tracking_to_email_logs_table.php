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
        Schema::table('email_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('email_logs', 'attachment_sent')) {
                $table->boolean('attachment_sent')->nullable()->after('error_message')->index();
            }
            if (!Schema::hasColumn('email_logs', 'attachment_name')) {
                $table->string('attachment_name')->nullable()->after('attachment_sent');
            }
            if (!Schema::hasColumn('email_logs', 'attachment_size')) {
                $table->unsignedBigInteger('attachment_size')->nullable()->after('attachment_name');
            }
            if (!Schema::hasColumn('email_logs', 'attachment_error')) {
                $table->text('attachment_error')->nullable()->after('attachment_size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            foreach (['attachment_error', 'attachment_size', 'attachment_name', 'attachment_sent'] as $col) {
                if (Schema::hasColumn('email_logs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

