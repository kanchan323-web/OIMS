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
            $table->integer('dn_no')->nullable()->after('RID');
            $table->string('remarks')->nullable()->after('supplier_rig_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_requesters', function (Blueprint $table) {
            $table->dropColumn([
                'dn_no','remarks'
            ]);
        });
    }
};
