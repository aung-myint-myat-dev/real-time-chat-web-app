<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{conversation}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{conversation}/read', [ChatController::class, 'markAsRead'])->name('chats.read');
    
    Route::post('/users/update-last-seen-at',  [UserController::class, 'updateLastSeenAt']);
    
    Route::get('/chats/{conversation}/messages', [MessageController::class, 'index'])->name('message.index');
        Route::post('/messages', [MessageController::class, 'store'])->name('message.store');

        Route::get('/users/search', [UserSearchController::class, 'index']);

        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'index')->name('profile.index');
            Route::put('profile', 'update')->name('profile.update');
            Route::delete('profile', 'delete')->name('profile.delete');
            Route::put('profile/avatar', 'uploadProfileImage')->name('profile.avatar.upload');
            Route::delete('profile/avatar', 'deleteProfileImage')->name('profile.avatar.delete');
            Route::get('profile/{id}', 'show')->name('profile.show');
        });

        Route::post('/users/update-last-seen-at',  [UserController::class, 'updateLastSeenAt']);
        Route::post('/messages', [MessageController::class, 'store'])->name('message.store');
        Route::get('/users/search', [UserSearchController::class, 'index']);
    });
