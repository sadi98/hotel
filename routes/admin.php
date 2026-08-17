<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/hotel', function () {
        return view('admin.hotel.index');
    })->name('hotel.index');

    Route::get('/booking', function () {
        return view('admin.booking.index');
    })->name('booking.index');
});