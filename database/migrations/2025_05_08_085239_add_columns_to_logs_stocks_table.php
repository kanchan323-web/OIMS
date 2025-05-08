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
        Schema::table('logs_stocks', function (Blueprint $table) {
            $table->string('new_value',50)->nullable()->after('used_spareable');
            $table->string('used_value',50)->nullable()->after('new_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_stocks', function (Blueprint $table) {
            $table->dropColumn([
                'new_value',
                'used_value',
            ]);
        });
    }
};
