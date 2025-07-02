<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained('return_requests')->cascadeOnDelete();
            $table->foreignId('item_unit_id')->constrained('item_units')->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('condition');
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
