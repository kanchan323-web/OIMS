<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('logs_categories', function (Blueprint $table) {
            $table->dropUnique('logs_categories_category_name_unique'); 
        });
    }

    public function down()
    {
        Schema::table('logs_categories', function (Blueprint $table) {
            $table->unique('category_name'); // Revert by adding unique back
        });
    }

};
