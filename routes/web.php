<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__.'/auth.php';
require __DIR__.'/channels.php';

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware(['auth'])
    ->group(function () {

        // Chats
        Route::prefix('/chats')
            ->name('chats.')
            ->controller(ChatController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{conversation}', 'show')->name('show');
                Route::post('/{conversation}/read', 'markAsRead')->name('read');
                Route::delete('/{conversation}', 'destroy')->name('destroy');
            });

        // Messages
        Route::name('message.')
            ->controller(MessageController::class)
            ->group(function () {
                Route::post('/messages', 'store')->name('store');
                Route::put('/messages/{message}', 'update')->name('update');
                Route::delete('/messages/{message}', 'destroy')->name('destroy');
                Route::post('/messages/{message}/mark-as-read', 'markAsRead')->name('read');
                Route::get('/chats/{conversation}/messages', 'index')->name('index');
            });

        Route::get('/users/search', [UserSearchController::class, 'index'])->name('users.search');

        // Profile
        Route::prefix('/profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update');
            Route::delete('/', 'delete')->name('delete');
            Route::put('/avatar', 'uploadProfileImage')->name('avatar.upload');
            Route::delete('/avatar', 'deleteProfileImage')->name('avatar.delete');
            Route::get('/{user}', 'show')->name('show');
        });

        Route::post('/users/update-last-seen-at', [UserController::class, 'updateLastSeenAt']);
    });
