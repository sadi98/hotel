<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordStaffController;
use App\Http\Controllers\Admin\Auth\LoginStaffController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\MenuPackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentWebhookController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\RestaurantOrderController;
use App\Http\Controllers\Admin\RestaurantReservationController;
use App\Http\Controllers\Admin\RestaurantSettingController;
use App\Http\Controllers\Admin\RestaurantTableController;
use App\Http\Controllers\Admin\StaffAccountController;
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
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/dashboard',
            'admin.dashboard.index'
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [ProfileAdminController::class, 'index']
        )->name('management.profile');

        Route::patch(
            '/profile/update',
            [ProfileAdminController::class, 'updateProfile']
        )->name('management.profile.update');

        Route::patch(
            '/profile/avatar',
            [ProfileAdminController::class, 'updateAvatar']
        )->name('management.profile.avatar');

        Route::patch(
            '/profile/password',
            [ProfileAdminController::class, 'updatePassword']
        )->name('management.profile.password');

        Route::post(
            '/logout',
            [LoginStaffController::class, 'destroy']
        )->name('management.logout');

        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/categories',
            [CategoryController::class, 'index']
        )->name('management.categories.index');

        Route::get(
            '/restaurant/categories/create',
            [CategoryController::class, 'create']
        )->name('management.categories.create');

        Route::post(
            '/restaurant/categories',
            [CategoryController::class, 'store']
        )->name('management.categories.store');

        Route::get(
            '/restaurant/categories/{category}/edit',
            [CategoryController::class, 'edit']
        )->name('management.categories.edit');

        Route::put(
            '/restaurant/categories/{category}',
            [CategoryController::class, 'update']
        )->name('management.categories.update');

        Route::delete(
            '/restaurant/categories/{category}',
            [CategoryController::class, 'destroy']
        )->name('management.categories.destroy');

        /*
        |--------------------------------------------------------------------------
        | MENU ITEM
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/menu-items',
            [MenuItemController::class, 'index']
        )->name('management.menu-items.index');

        Route::get(
            '/restaurant/menu-items/create',
            [MenuItemController::class, 'create']
        )->name('management.menu-items.create');

        Route::post(
            '/restaurant/menu-items',
            [MenuItemController::class, 'store']
        )->name('management.menu-items.store');

        Route::get(
            '/restaurant/menu-items/{menuItem}/edit',
            [MenuItemController::class, 'edit']
        )->name('management.menu-items.edit');

        Route::put(
            '/restaurant/menu-items/{menuItem}',
            [MenuItemController::class, 'update']
        )->name('management.menu-items.update');

        Route::delete(
            '/restaurant/menu-items/{menuItem}',
            [MenuItemController::class, 'destroy']
        )->name('management.menu-items.destroy');

        Route::patch(
            '/restaurant/menu-items/{menuItem}/availability',
            [MenuItemController::class, 'toggleAvailability']
        )->name('management.menu-items.availability');

        /*
        |--------------------------------------------------------------------------
        | MENU PACKAGE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/menu-packages',
            [MenuPackageController::class, 'index']
        )->name('management.menu-packages.index');

        Route::get(
            '/restaurant/menu-packages/create',
            [MenuPackageController::class, 'create']
        )->name('management.menu-packages.create');

        Route::post(
            '/restaurant/menu-packages',
            [MenuPackageController::class, 'store']
        )->name('management.menu-packages.store');

        Route::get(
            '/restaurant/menu-packages/{menuPackage}/edit',
            [MenuPackageController::class, 'edit']
        )->name('management.menu-packages.edit');

        Route::put(
            '/restaurant/menu-packages/{menuPackage}',
            [MenuPackageController::class, 'update']
        )->name('management.menu-packages.update');

        Route::delete(
            '/restaurant/menu-packages/{menuPackage}',
            [MenuPackageController::class, 'destroy']
        )->name('management.menu-packages.destroy');

        Route::patch(
            '/restaurant/menu-packages/{menuPackage}/availability',
            [MenuPackageController::class, 'toggleAvailability']
        )->name('management.menu-packages.availability');

        /*
        |--------------------------------------------------------------------------
        | RESTAURANT TABLE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/tables',
            [RestaurantTableController::class, 'index']
        )->name('management.tables.index');

        Route::get(
            '/restaurant/tables/create',
            [RestaurantTableController::class, 'create']
        )->name('management.tables.create');

        Route::post(
            '/restaurant/tables',
            [RestaurantTableController::class, 'store']
        )->name('management.tables.store');

        Route::get(
            '/restaurant/tables/{restaurantTable}/edit',
            [RestaurantTableController::class, 'edit']
        )->name('management.tables.edit');

        Route::put(
            '/restaurant/tables/{restaurantTable}',
            [RestaurantTableController::class, 'update']
        )->name('management.tables.update');

        Route::delete(
            '/restaurant/tables/{restaurantTable}',
            [RestaurantTableController::class, 'destroy']
        )->name('management.tables.destroy');

        Route::patch(
            '/restaurant/tables/{restaurantTable}/status',
            [RestaurantTableController::class, 'toggleStatus']
        )->name('management.tables.status');

        Route::post(
            '/restaurant/tables/{restaurantTable}/regenerate-qr',
            [RestaurantTableController::class, 'regenerateQr']
        )->name('management.tables.regenerate-qr');

        /*
        |--------------------------------------------------------------------------
        | RESTAURANT ORDER
        |--------------------------------------------------------------------------
        |
        | Route create harus berada sebelum route /{order} agar kata "create"
        | tidak dianggap sebagai parameter order.
        |
        */

        Route::get(
            '/restaurant/orders',
            [RestaurantOrderController::class, 'index']
        )->name('management.orders.index');

        Route::get(
            '/restaurant/orders/create',
            [RestaurantOrderController::class, 'create']
        )->name('management.orders.create');

        Route::post(
            '/restaurant/orders',
            [RestaurantOrderController::class, 'store']
        )->name('management.orders.store');

        Route::get(
            '/restaurant/orders/{order}/edit',
            [RestaurantOrderController::class, 'edit']
        )->name('management.orders.edit');

        Route::put(
            '/restaurant/orders/{order}',
            [RestaurantOrderController::class, 'update']
        )->name('management.orders.update');

        Route::get(
            '/restaurant/orders/{order}',
            [RestaurantOrderController::class, 'show']
        )->name('management.orders.show');

        Route::patch(
            '/restaurant/orders/{order}/status',
            [RestaurantOrderController::class, 'updateStatus']
        )->name('management.orders.status');

        Route::patch(
            '/restaurant/orders/{order}/items/{orderItem}/status',
            [RestaurantOrderController::class, 'updateItemStatus']
        )->name('management.orders.items.status');

        Route::post(
            '/restaurant/orders/{order}/payment',
            [RestaurantOrderController::class, 'pay']
        )->name('management.orders.pay');

        /*
        |--------------------------------------------------------------------------
        | RESTAURANT RESERVATION
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/reservations',
            [RestaurantReservationController::class, 'index']
        )->name('management.reservations.index');

        Route::get(
            '/restaurant/reservations/create',
            [RestaurantReservationController::class, 'create']
        )->name('management.reservations.create');

        Route::post(
            '/restaurant/reservations',
            [RestaurantReservationController::class, 'store']
        )->name('management.reservations.store');

        Route::get(
            '/restaurant/reservations/{reservation}/edit',
            [RestaurantReservationController::class, 'edit']
        )->name('management.reservations.edit');

        Route::match(
            ['put', 'patch'],
            '/restaurant/reservations/{reservation}',
            [RestaurantReservationController::class, 'update']
        )->name('management.reservations.update');

        Route::get(
            '/restaurant/reservations/{reservation}',
            [RestaurantReservationController::class, 'show']
        )->name('management.reservations.show');

        Route::delete(
            '/restaurant/reservations/{reservation}',
            [RestaurantReservationController::class, 'destroy']
        )->name('management.reservations.destroy');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/payments',
            [PaymentController::class, 'index']
        )->name('management.payments.index');

        Route::get(
            '/restaurant/payments/{payment}',
            [PaymentController::class, 'show']
        )->name('management.payments.show');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT WEBHOOK
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/payment-webhooks',
            [PaymentWebhookController::class, 'index']
        )->name('management.payment-webhooks.index');

        Route::get(
            '/restaurant/payment-webhooks/{paymentWebhook}',
            [PaymentWebhookController::class, 'show']
        )->name('management.payment-webhooks.show');

        /*
        |--------------------------------------------------------------------------
        | RESTAURANT SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/restaurant/settings',
            [RestaurantSettingController::class, 'edit']
        )->name('management.restaurant-settings.edit');

        Route::patch(
            '/restaurant/settings',
            [RestaurantSettingController::class, 'update']
        )->name('management.restaurant-settings.update');

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/customers',
            [CustomerController::class, 'index']
        )->name('management.customers.index');

        Route::get(
            '/customers/{user}',
            [CustomerController::class, 'show']
        )->name('management.customers.show');

        /*
        |--------------------------------------------------------------------------
        | STAFF ACCOUNT
        |--------------------------------------------------------------------------
        |
        | Pemeriksaan role Admin tetap dilakukan kembali di dalam
        | StaffAccountController.
        |
        */

        Route::get(
            '/staff-accounts',
            [StaffAccountController::class, 'index']
        )->name('management.staff-accounts.index');

        Route::get(
            '/staff-accounts/create',
            [StaffAccountController::class, 'create']
        )->name('management.staff-accounts.create');

        Route::post(
            '/staff-accounts',
            [StaffAccountController::class, 'store']
        )->name('management.staff-accounts.store');

        Route::get(
            '/staff-accounts/{user}/edit',
            [StaffAccountController::class, 'edit']
        )->name('management.staff-accounts.edit');

        Route::put(
            '/staff-accounts/{user}',
            [StaffAccountController::class, 'update']
        )->name('management.staff-accounts.update');

        Route::delete(
            '/staff-accounts/{user}',
            [StaffAccountController::class, 'destroy']
        )->name('management.staff-accounts.destroy');
    });
