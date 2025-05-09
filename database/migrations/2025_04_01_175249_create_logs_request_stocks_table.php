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
        Schema::create('logs_request_stocks', function (Blueprint $table) {
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
            $table->string('used_spareable')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('reason_for_rejection')->nullable();
            $table->text('query')->nullable();
            $table->text('remarks');
            $table->string('supplier_location_name');
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
        Schema::dropIfExists('logs_request_stocks');
    }
};
