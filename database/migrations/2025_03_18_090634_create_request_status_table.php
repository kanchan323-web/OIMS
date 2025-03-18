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
        Schema::create('request_status', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_status');
    }
};
