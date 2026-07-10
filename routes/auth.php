<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])
->controller(AuthController::class)
->prefix('auth')
->group(function () {
    Route::get('register', 'showRegisterForm')->name('register');
    Route::post('register', 'register')->name('register.post');
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login')->name('login.post');
});

Route::middleware(['auth'])
->controller(AuthController::class)
->group(function () {
    Route::get('/email/verify', 'verifyEmailPage')->name('verification.notice');
    Route::middleware('signed')->get('/email/verify/{id}/{hash}', 'verifyEmail')->name('verification.verify');
    Route::middleware('throttle:6,1')->post('/email/verification-notification', 'resendVerificationLink')->name('verification.send');
    Route::post('logout', 'logout')->name('auth.logout.post');
});