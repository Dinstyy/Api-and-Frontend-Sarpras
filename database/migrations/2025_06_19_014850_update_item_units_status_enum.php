<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemUnitsStatusEnum extends Migration
{
    public function up()
    {
        Schema::table('item_units', function (Blueprint $table) {
            $table->enum('status', ['available', 'borrowed', 'out_of_stock', 'unknown', 'unavailable'])->default('available')->change();
        });
    }

    public function down()
    {
        Schema::table('item_units', function (Blueprint $table) {
            $table->enum('status', ['available', 'borrowed', 'unknown', 'unavailable'])->default('available')->change();
        });
    }
}
