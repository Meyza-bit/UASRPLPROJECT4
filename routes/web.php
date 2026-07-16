<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RiwayatController;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
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

// Halaman pesan, profil, pembayaran (cuma buat yang SUDAH login)
Route::middleware('auth')->group(function () {
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::post('/pesan/tambah', [PesanController::class, 'tambah'])->name('pesan.tambah');
    Route::post('/pesan/hapus', [PesanController::class, 'hapus'])->name('pesan.hapus');
    Route::post('/pesan/jadwal', [PesanController::class, 'jadwal'])->name('pesan.jadwal');
    Route::post('/pesan/simpan', [PesanController::class, 'simpan'])->name('pesan.simpan');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password', [ProfilController::class, 'gantiPassword'])->name('profil.password');

    Route::get('/pembayaran/{penyewaan}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{penyewaan}/upload', [PembayaranController::class, 'upload'])->name('pembayaran.upload');
    Route::get('/pembayaran/{penyewaan}/berhasil', [PembayaranController::class, 'berhasil'])->name('pembayaran.berhasil');
    Route::post('/pembayaran/{penyewaan}/batal', [PembayaranController::class, 'batal'])->name('pembayaran.batal');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{penyewaan}', [RiwayatController::class, 'show'])->name('riwayat.show');
});