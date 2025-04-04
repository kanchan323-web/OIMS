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
        Schema::table('logs_stocks', function (Blueprint $table) {
            $table->integer('stock_id')->after('id');
            $table->unsignedBigInteger('creater_id');
            $table->string('creater_type');
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type');
            $table->text('message');
            $table->string('action',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_stocks', function (Blueprint $table) {
            Schema::dropColumn('stock_id','creater_id','creater_type','receiver_id','receiver_type','message','action');
        });
    }
};
