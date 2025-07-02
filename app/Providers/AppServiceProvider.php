<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\User;
use App\Models\ItemUnit;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;

use App\Observers\ItemObserver;
use App\Observers\UserObserver;
use App\Observers\ItemUnitObserver;
use App\Observers\BorrowRequestObserver;
use App\Observers\ReturnRequestObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Item::observe(ItemObserver::class);
        ItemUnit::observe(ItemUnitObserver::class);
        BorrowRequest::observe(BorrowRequestObserver::class);
        ReturnRequest::observe(ReturnRequestObserver::class);
    }
}
