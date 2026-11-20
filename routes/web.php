<?php

use App\Http\Controllers\Admin\AspirasiController as AdminAspirasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/aspirasi/create', [AspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi-saya', [AspirasiController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/{aspirasi}', [AspirasiController::class, 'show'])->name('aspirasi.show');
    Route::get('/aspirasi/{aspirasi}/edit', [AspirasiController::class, 'edit'])->name('aspirasi.edit');
    Route::put('/aspirasi/{aspirasi}', [AspirasiController::class, 'update'])->name('aspirasi.update');
    Route::delete('/aspirasi/{aspirasi}', [AspirasiController::class, 'destroy'])->name('aspirasi.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::get('/aspirasi', [AdminAspirasiController::class, 'index'])->name('aspirasi.index');
        Route::get('/aspirasi/{aspirasi}', [AdminAspirasiController::class, 'show'])->name('aspirasi.show');
        Route::match(['put', 'patch'], '/aspirasi/{aspirasi}', [AdminAspirasiController::class, 'update'])->name('aspirasi.update');
        Route::delete('/aspirasi/{aspirasi}', [AdminAspirasiController::class, 'destroy'])->name('aspirasi.destroy');
    });
