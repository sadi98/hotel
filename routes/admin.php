<?php

use App\Http\Controllers\Admin\Auth\LoginStaffController;
use App\Http\Controllers\Admin\RestaurantOrderController;
use App\Http\Controllers\Admin\RestaurantReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest.staff')->group(function () {
    Route::get('/management/login', [LoginStaffController::class, 'index'])->name('login.staff');
    Route::post('/management/login', [LoginStaffController::class, 'store'])->name('login.staff.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth.admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');
});

Route::prefix('staff')->name('staff.')->middleware('auth.staff')->group(function () {
    Route::view('/dashboard', 'admin.dashboard.index')->name('dashboard');
});

Route::prefix('management')->name('management.')->middleware('auth.staff_or_admin')->group(function () {
    Route::post('/logout', [LoginStaffController::class, 'destroy'])->name('logout');
    Route::get('/restaurant/orders', [RestaurantOrderController::class, 'index'])->name('orders.index');
    Route::get('/restaurant/orders/{order}', [RestaurantOrderController::class, 'show'])->name('orders.show');
    Route::patch('/restaurant/orders/{order}/status', [RestaurantOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/restaurant/orders/{order}/payment', [RestaurantOrderController::class, 'pay'])->name('orders.pay');
    Route::get('/restaurant/reservations', [RestaurantReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/restaurant/reservations/{reservation}', [RestaurantReservationController::class, 'update'])->name('reservations.update');
});
