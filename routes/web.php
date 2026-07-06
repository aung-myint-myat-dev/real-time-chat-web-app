<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__.'/auth.php';

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware(['auth'])->get('/chat', function () {
    return Inertia::render('Chat');
})->name('chatboard');
