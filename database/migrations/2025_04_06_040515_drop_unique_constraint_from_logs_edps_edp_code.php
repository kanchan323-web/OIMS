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
        Schema::table('logs_edps', function (Blueprint $table) {
            $table->dropUnique('logs_edps_edp_code_unique'); // or use your actual index name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_edps', function (Blueprint $table) {
            $table->unique('edp_code');
        });
    }
};
