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
        Schema::create('request_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_id')->nullable();
            $table->string('section');
            $table->string('stock_item');
            $table->string('stock_code');
            $table->integer('request_quantity');
            $table->integer('qty');
            $table->string('measurement');
            $table->string('new_spareable');
            $table->string('used_spareable');
            $table->text('remarks');
            $table->string('supplier_location_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_stocks');
    }
};
