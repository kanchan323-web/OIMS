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
        Schema::table('rig_users', function (Blueprint $table) {
            $table->string('location_id', 60)->unique()->change();
            $table->string('name', 60)->unique()->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('rig_users', function (Blueprint $table) {
            $table->string('location_id', 60)->unique(false)->change();
            $table->string('name', 60)->unique(false)->change();
        });
    }
};
