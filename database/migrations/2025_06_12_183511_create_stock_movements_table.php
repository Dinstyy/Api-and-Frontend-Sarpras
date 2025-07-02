<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_unit_id')->constrained('item_units')->cascadeOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->dateTime('movement_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
