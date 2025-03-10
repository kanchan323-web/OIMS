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
        Schema::create('edps', function (Blueprint $table) {
            $table->id();
            $table->string('edp_code',50);
            $table->string('category',50);
            $table->string('description',200);
            $table->string('section',50);
            $table->string('measurement',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edps');
    }
};
