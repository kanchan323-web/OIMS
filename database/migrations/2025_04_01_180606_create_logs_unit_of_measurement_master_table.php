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
        Schema::create('logs_unit_of_measurement_master', function (Blueprint $table) {
            $table->id('id'); 
            $table->string('unit_name');
            $table->String('type_of_unit'); 
            $table->string('abbreviation', 10); 
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
        Schema::dropIfExists('logs_unit_of_measurement_master');
    }
};
