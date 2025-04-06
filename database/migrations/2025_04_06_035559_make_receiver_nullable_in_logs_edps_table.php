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
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
            $table->string('receiver_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_edps', function (Blueprint $table) {
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
            $table->string('receiver_type')->nullable(false)->change();
        });
    }
};
