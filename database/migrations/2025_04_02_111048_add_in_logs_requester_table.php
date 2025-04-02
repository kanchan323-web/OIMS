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
        Schema::table('logs_requesters', function (Blueprint $table) {
            $table->integer('requester_stock_id')->after('stock_id');
      
            $table->string('RID', 255)->unique()->after('status');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_requesters', function (Blueprint $table) {
            $table->dropColumn('requester_stock_id');
            $table->dropColumn('RID');
        });
    }
};
