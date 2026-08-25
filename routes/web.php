<?php

use App\Http\Controllers\Users\Auth\ForgotPasswordController;
use App\Http\Controllers\Users\Auth\GoogleController;
use App\Http\Controllers\Users\Auth\LoginController;
use App\Http\Controllers\Users\Auth\RegisterController;
use App\Http\Controllers\Users\ProfileUserController;
use App\Http\Controllers\Users\SettingController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\MenuController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ReservationController;
use App\Http\Controllers\User\TableScanController;
use App\Http\Controllers\Users\CameraController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin and Staff Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/admin.php';

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('users.home.index');
})->name('home');

Route::get('/room', function () {
    return view('users.room.detail');
});

Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
Route::get('/menus/{menuItem:slug}', [MenuController::class, 'showMenu'])->name('menus.show');
Route::get('/packages/{menuPackage:slug}', [MenuController::class, 'showPackage'])->name('packages.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/table/scan/{qrToken}', [TableScanController::class, 'scan'])->name('tables.scan');
Route::delete('/table/scan', [TableScanController::class, 'clear'])->name('tables.scan.clear');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::get('/reservations/available', [ReservationController::class, 'availableTables'])->name('reservations.available');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{payment}/retry', [PaymentController::class, 'retry'])->name('payments.retry');
Route::get('/payments/{payment}/finish', [PaymentController::class, 'finish'])->name('payments.finish');
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

/*
|--------------------------------------------------------------------------
| Customer Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest.user')->group(function () {
    Route::get(
        '/login',
        [LoginController::class, 'index']
    )->name('login');

    Route::post(
        '/login/store',
        [LoginController::class, 'store']
    )->name('login.store');

    Route::get(
        '/register',
        [RegisterController::class, 'index']
    )->name('register');

    Route::post(
        '/register/store',
        [RegisterController::class, 'store']
    )->name('register.store');

    Route::get(
        '/forgot-password',
        [ForgotPasswordController::class, 'index']
    )->name('password.request');

    Route::post(
        '/forgot-password',
        [ForgotPasswordController::class, 'sendOtp']
    )->name('password.email');

    Route::get(
        '/verify-otp',
        [ForgotPasswordController::class, 'showVerifyOtp']
    )->name('password.otp');

    Route::post(
        '/verify-otp',
        [ForgotPasswordController::class, 'verifyOtp']
    )->name('password.otp.verify');

    Route::post(
        '/resend-otp',
        [ForgotPasswordController::class, 'resendOtp']
    )->name('password.otp.resend');

    Route::get(
        '/reset-password',
        [ForgotPasswordController::class, 'showResetPassword']
    )->name('password.reset.form');

    Route::post(
        '/reset-password',
        [ForgotPasswordController::class, 'resetPassword']
    )->name('password.reset.update');

    Route::get(
        '/auth/google/redirect',
        [GoogleController::class, 'redirect']
    )->name('google.redirect');

    Route::get(
        '/auth/google/callback',
        [GoogleController::class, 'callback']
    )->name('google.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth.user')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get(
        '/setting',
        [SettingController::class, 'index']
    )->name('setting');

    Route::get(
        '/profile',
        [ProfileUserController::class, 'index']
    )->name('profile');

    Route::get('camera', [CameraController::class, 'index'])->name('camera');

    Route::post(
        '/logout',
        [LoginController::class, 'destroy']
    )->name('logout');
});
