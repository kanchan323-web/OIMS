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
        Schema::create('logs_requesters', function (Blueprint $table) {
            $table->id();
            $table->string('status',15)->default('Pendding');
            $table->integer('available_qty');
            $table->integer('requested_qty');
            $table->integer('stock_id');
            $table->integer('requester_id');
            $table->integer('requester_rig_id');
            $table->integer('supplier_id');
            $table->integer('supplier_rig_id');
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
        Schema::dropIfExists('logs_requesters');
    }
};
