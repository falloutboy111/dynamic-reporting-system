<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\OrganisationManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\ReportBuilder;
use App\Livewire\User\ReportsList;
use App\Livewire\User\ReportView;

// Root redirect based on authentication and role
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()->hasRole('user')) {
        return redirect()->route('user.dashboard');
    }

    auth()->logout();
    return redirect()->route('login');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::get('organisations', OrganisationManagement::class)->name('organisations.index');
    Route::get('users', UserManagement::class)->name('users.index');
    Route::get('reports', ReportBuilder::class)->name('reports.index');
});

// User Routes
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::view('dashboard', 'user.dashboard')->name('dashboard');
    Route::get('reports', ReportsList::class)->name('reports.index');
    Route::get('reports/{id}', ReportView::class)->name('reports.view');
});

// Shared Routes (both admin and user)
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
