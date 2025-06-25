<?php

namespace App\Providers;


use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Repositories\CourseRepository;
use App\Repositories\PricingRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TransactionRepository;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\PricingRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // dalam singleton ini ada interface dan juga implementasi classnya daftarkan service dan repository
        $this->app->singleton(PricingRepositoryInterface::class, PricingRepository::class);
        $this->app->singleton(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->singleton(CourseRepositoryInterface::class, CourseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
    }
}
