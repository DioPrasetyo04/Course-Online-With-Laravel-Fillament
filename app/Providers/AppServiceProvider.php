<?php

namespace App\Providers;

use App\Models\Transaction;
use TransactionRepositoryInterface;
use App\Observers\TransactionObserver;
use App\Repositories\PricingRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\PricingRepositoryInterface;
use TransactionRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // dalam singleton ini ada interface dan juga implementasi classnya
        $this->app->singleton(PricingRepositoryInterface::class, PricingRepository::class);
        $this->app->singleton(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
    }
}
