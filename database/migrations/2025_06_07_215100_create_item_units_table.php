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
        Schema::create('item_units', function (Blueprint $table) {
            $table->id();
            $table->string("unit_code")->unique();
            $table->string("merk");
            $table->string("condition");
            $table->text("notes")->nullable();
            $table->string("diperoleh_dari");
            $table->date("diperoleh_tanggal");
            $table->enum("status", ["available","borrowed","unknown","unavailable"])->default("available");
            $table->integer("quantity")->default(1);
            $table->string("qr_image");
            $table->foreignId("item_id")->constrained("items")->cascadeOnDelete();
            $table->foreignId("warehouse_id")->constrained("warehouses")->cascadeOnDelete();
            $table->string("current_location")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_units');
    }
};
