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
        Schema::table('request_status', function (Blueprint $table) {
            $table->tinyInteger('is_read')->default(0)->after('rig_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_status', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};
