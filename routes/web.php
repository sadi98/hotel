<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';

Route::get('/', function () {
    return view('users.home.index');
});

Route::get('/setting', [App\Http\Controllers\Users\SettingController::class, 'index'])->name('setting');
Route::get('/profile', [App\Http\Controllers\Users\ProfileUserController::class, 'index'])->name('profile');