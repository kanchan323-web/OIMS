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
        Schema::table('logs_request_status', function (Blueprint $table) {
            $table->string('sent_to', 100)->nullable()->after('rig_id');
            $table->string('sent_from', 100)->nullable()->after('sent_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_request_status', function (Blueprint $table) {
            $table->dropColumn('sent_to');
            $table->dropColumn('sent_from');
        });
    }
};
