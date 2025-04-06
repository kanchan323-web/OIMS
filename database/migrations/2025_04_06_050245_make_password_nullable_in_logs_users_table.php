<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('logs_users', function (Blueprint $table) {
        $table->string('password')->nullable()->change();
        $table->unsignedBigInteger('receiver_id')->nullable()->change();
        $table->string('receiver_type')->nullable()->change();
        $table->dropUnique(['email']); // drops unique constraint on 'email'
    });
}

public function down()
{
    Schema::table('logs_users', function (Blueprint $table) {
        $table->string('password')->nullable(false)->change();
        $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        $table->string('receiver_type')->nullable(false)->change();
        $table->unique('email');
    });
}
};
