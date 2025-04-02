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
        Schema::table('notifications', function (Blueprint $table) {
            // Step 1: Drop the primary key constraint and remove the existing 'id' column
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            // Step 2: Add a new auto-increment integer 'id' column as the primary key
            $table->bigIncrements('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Step 1: Drop the integer primary key and column
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            // Step 2: Restore the UUID-based 'id' column
            $table->uuid('id')->primary();
        });
    }
};
