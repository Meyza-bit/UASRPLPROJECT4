<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');

// Halaman login & daftar (cuma buat yang BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [LoginController::class, 'login']);

    Route::get('/daftar', [LoginController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [LoginController::class, 'register']);

    Route::get('/lupa-password', [PasswordResetController::class, 'showLupaPassword'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'kirimLink'])->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Logout (cuma buat yang SUDAH login)
Route::post('/keluar', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');