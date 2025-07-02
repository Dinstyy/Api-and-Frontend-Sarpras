<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['handle_by']);

            // Make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Re-add foreign keys with onDelete('set null')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('handle_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Drop modified foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['handle_by']);

            // Revert user_id to non-nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Revert to cascade
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('handle_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
