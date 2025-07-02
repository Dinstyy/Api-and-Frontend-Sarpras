<?php

namespace Database\Seeders;

// use App\Models\BorrowDetail;
// use App\Models\BorrowRequest;
// use App\Models\Category;
// use App\Models\Item;
// use App\Models\ItemUnit;
// use App\Models\ReturnDetail;
// use App\Models\ReturnRequest;
use App\Models\User;
// use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");

        User::query()->truncate();
        User::query()->create([
            'name' => "admin",
            'email' => "admin@sarpras.com",
            'password' => Hash::make("petugas123"),
            'role' => "admin",
        ]);

        // Category::query()->truncate();

        // Warehouse::query()->truncate();

        // Item::query()->truncate();

        // ItemUnit::query()->truncate();

        // BorrowRequest::query()->truncate();
        // BorrowDetail::query()->truncate();
        // ReturnRequest::query()->truncate();
        // ReturnDetail::query()->truncate();

        DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}
