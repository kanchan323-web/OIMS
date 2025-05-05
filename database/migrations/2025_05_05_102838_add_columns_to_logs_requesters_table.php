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
            $table->string('decline_msg')->nullable();
            $table->string('query_msg')->nullable();
            $table->string('supplier_qty')->nullable();
            $table->string('supplier_new_spareable')->nullable();
            $table->string('supplier_used_spareable')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('logs_requesters', function (Blueprint $table) {
            $table->dropColumn([
                'decline_msg',
                'query_msg',
                'supplier_qty',
                'supplier_new_spareable',
                'supplier_used_spareable',
            ]);
        });
    }
};
