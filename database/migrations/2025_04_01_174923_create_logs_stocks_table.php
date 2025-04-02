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
        Schema::create('logs_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('location_id',50);
            $table->string('location_name',50);
            $table->string('edp_code',50);
            $table->string('category',50);
            $table->string('description',200);
            $table->string('section',50);
            $table->string('qty',50);
            $table->string('measurement',50);
            $table->string('new_spareable',50);
            $table->string('used_spareable',50);
            $table->string('remarks',200);
            $table->integer('user_id')->nullable();
            $table->integer('rig_id')->nullable();
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
        Schema::dropIfExists('logs_stocks');
    }
};
