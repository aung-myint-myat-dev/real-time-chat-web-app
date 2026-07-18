<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';
require __DIR__ . '/channels.php';

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/chats', [ChatController::class, 'index'])->name('chatboard');
        Route::post('/chats', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chats/{conversation}', [ChatController::class, 'show'])->name('chat.show');

        Route::post('/messages', [MessageController::class, 'store'])->name('message.store');

        Route::get('/users/search', [UserSearchController::class, 'index']);

        Route::get('/settings', function () {
            return Inertia::render('Setting');
        });

        Route::get('/profile', function () {
            return Inertia::render('Profile');
        });

        Route::get('/saved/messages', function () {
            return Inertia::render('SavedMessages');
        });
    });
