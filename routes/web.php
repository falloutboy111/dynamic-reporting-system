<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('client-sync', 'client-sync')
    ->middleware(['auth'])
    ->name('client-sync.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('users', 'users')
    ->middleware(['auth'])
    ->name('users.index');

Route::view('logs', 'logs')
    ->middleware(['auth'])
    ->name('logs.index');

Route::view('reports', 'reports')
    ->middleware(['auth'])
    ->name('reports.index');

require __DIR__.'/auth.php';
