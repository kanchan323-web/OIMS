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
        Schema::create('logs_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 50);
            $table->string('email', 60)->unique();
            $table->string('password', 150);
            $table->tinyInteger('user_status')->default(1);
            $table->enum('user_type', ['user', 'admin'])->default('user');
            $table->integer('rig_id');
            $table->rememberToken();
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
        Schema::dropIfExists('logs_users');
    }
};
