<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/login', function () {
    return Inertia::render('auth/Login');
});

Route::get('/register', function () {
    return Inertia::render('auth/Register');
});

Route::get('/chat', function () {
    return Inertia::render('Chat');
});