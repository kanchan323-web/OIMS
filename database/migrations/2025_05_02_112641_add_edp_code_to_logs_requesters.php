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
            $table->string('edp_code')->nullable()->after('stock_id'); // adjust the position if needed
        });
    }

    public function down(): void
    {
        Schema::table('logs_requesters', function (Blueprint $table) {
            $table->dropColumn('edp_code');
        });
    }
};
