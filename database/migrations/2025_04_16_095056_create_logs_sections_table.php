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
        Schema::create('logs_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id')->nullable();
             $table->string('section_name',60);
            $table->timestamps();
            $table->unsignedBigInteger('creater_id');
            $table->string('creater_type');
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_sections');
    }
};
