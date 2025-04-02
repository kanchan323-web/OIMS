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
        Schema::create('logs_request_status', function (Blueprint $table) {
            $table->id();
            $table->string('decline_msg',100)->nullable();
            $table->string('query_msg',100)->nullable();
            $table->integer('supplier_qty')->nullable();
            $table->integer('supplier_new_spareable')->nullable();
            $table->integer('supplier_used_spareable')->nullable();
            $table->integer('request_id');
            $table->integer('status_id');
            $table->integer('user_id');
            $table->integer('rig_id');
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
        Schema::dropIfExists('logs_request_status');
    }
};
