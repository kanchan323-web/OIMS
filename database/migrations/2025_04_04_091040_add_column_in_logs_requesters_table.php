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
            $table->integer('request_id')->after('id');
            $table->string('action',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_requesters', function (Blueprint $table) {
            Schema::dropColumn('request_id','action');
        });
    }
};
