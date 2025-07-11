<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();
            $table->date("borrow_date_expected");
            $table->date("return_date_expected");
            $table->enum("status", ["pending", "approved", "returned", "rejected"])->default("pending");
            $table->text("reason");
            $table->text("notes")->nullable();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("handle_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};

