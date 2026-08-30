<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordStaffController;
use App\Http\Controllers\Admin\Auth\LoginStaffController;
use App\Http\Controllers\Admin\RestaurantOrderController;
use App\Http\Controllers\Admin\RestaurantReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MANAGEMENT GUEST
|--------------------------------------------------------------------------
*/

Route::middleware('guest.staff')->group(function () {
    Route::get(
        '/management/login',
        [LoginStaffController::class, 'index']
    )->name('login.staff');

    Route::post(
        '/management/login',
        [LoginStaffController::class, 'store']
    )->name('login.staff.store');

    Route::get(
        '/management/forgot-password',
        [ForgotPasswordStaffController::class, 'index']
    )->name('staff.password.request');

    Route::post(
        '/management/forgot-password',
        [ForgotPasswordStaffController::class, 'sendOtp']
    )->name('staff.password.otp.send');

    Route::get(
        '/management/verify-otp',
        [ForgotPasswordStaffController::class, 'showVerifyOtp']
    )->name('staff.password.otp.form');

    Route::post(
        '/management/verify-otp',
        [ForgotPasswordStaffController::class, 'verifyOtp']
    )->name('staff.password.otp.verify');

    Route::post(
        '/management/resend-otp',
        [ForgotPasswordStaffController::class, 'resendOtp']
    )->name('staff.password.otp.resend');

    Route::get(
        '/management/reset-password',
        [ForgotPasswordStaffController::class, 'showResetPassword']
    )->name('staff.password.reset.form');

    Route::post(
        '/management/reset-password',
        [ForgotPasswordStaffController::class, 'resetPassword']
    )->name('staff.password.reset.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN OR STAFF
|--------------------------------------------------------------------------
*/

Route::prefix('management')
    ->middleware('auth.staff_or_admin')
    ->group(function () {
        /*
         * Satu dashboard untuk admin dan staff.
         */
        Route::view(
            '/dashboard',
            'admin.dashboard.index'
        )->name('dashboard');

        Route::post(
            '/logout',
            [LoginStaffController::class, 'destroy']
        )->name('management.logout');

        Route::get(
            '/restaurant/orders',
            [RestaurantOrderController::class, 'index']
        )->name('management.orders.index');

        Route::get(
            '/restaurant/orders/{order}',
            [RestaurantOrderController::class, 'show']
        )->name('management.orders.show');

        Route::patch(
            '/restaurant/orders/{order}/status',
            [RestaurantOrderController::class, 'updateStatus']
        )->name('management.orders.status');

        Route::post(
            '/restaurant/orders/{order}/payment',
            [RestaurantOrderController::class, 'pay']
        )->name('management.orders.pay');

        Route::get(
            '/restaurant/reservations',
            [RestaurantReservationController::class, 'index']
        )->name('management.reservations.index');

        Route::patch(
            '/restaurant/reservations/{reservation}',
            [RestaurantReservationController::class, 'update']
        )->name('management.reservations.update');
    });
