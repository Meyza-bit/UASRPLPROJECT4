<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LoginController;

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
});

// Logout (cuma buat yang SUDAH login)
Route::post('/keluar', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');