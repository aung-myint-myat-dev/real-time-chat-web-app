<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register.post');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login.post');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verifyEmailPage'])->name('verification.notice');
    Route::middleware('signed')->get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::middleware('throttle:6,1')->post('/email/verification-notification', [AuthController::class, 'resendVerificationLink'])->name('verification.send');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout.post');
});