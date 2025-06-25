<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontHomeController;

Route::controller(FrontHomeController::class)->group(function () {
    Route::get('/', 'index')->name('front.index');
    Route::get('/pricing', 'pricing')->name('front.pricing');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:student')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard/subscription', 'subscription')->name('dashboard.subscription');
            Route::get('/dashboard/subscription/{transaction}', 'subscriptionDetails')->name('dashboard.subscription.details');
        });

        Route::controller(CourseController::class)->group(function () {
            Route::get('/dashboard/courses', 'index')->name('dashboard');
            Route::get('/dashboard/courses/{course:slug}', 'show')->name('dashboard.courses.show');
            Route::get('/dashboard/search/courses', 'search_courses')->name('dashboard.search.courses');

            Route::middleware('check.subscription')->group(function () {
                Route::get('/dashboard/join/{course:slug}', 'join')->name('dashboard.course.join');
                Route::get('/dashboard/learning/{course:slug}/{courseSection}/{sectionContent}', 'learning')->name('dashboard.course.learning');
                Route::get('/dashboard/learning/{course:slug}/finished', 'learning_finished')->name('dashboard.course.learning.finished');
            });
        });

        Route::controller(FrontHomeController::class)->group(function () {
            Route::get('/checkout/success', 'checkout_success')->name('checkout.success');
            Route::get('/checkout/{pricing}', 'checkout')->name('front.checkout');
            Route::post('/booking/payment/midtrans', 'paymentStoreMidtrans')->name('front.payment_store_midtrans');

            // method route akan di cek sama laravel apakah dia get atau post untuk menampilkan callback midtrans menggunakan match
            Route::match(['get', 'post'], '/booking/payment/midtrans/callback', 'paymentCallbackMidtrans')->name('front.payment_callback_midtrans');
        });
    });
});

require __DIR__ . '/auth.php';
