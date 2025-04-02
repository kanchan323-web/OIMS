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
        Schema::create('logs_rig_users', function (Blueprint $table) {

            $table->id();
            $table->string('location_id',60);
            $table->string('name',60);
            $table->timestamps();
            
              // New columns
              $table->unsignedBigInteger('creater_id');
              $table->string('creater_type');
              $table->unsignedBigInteger('receiver_id');
              $table->string('receiver_type');
              $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_rig_users');
    }
};
