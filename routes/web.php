<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananServisController;
use App\Http\Controllers\PembayaranController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/servis/pesan', [PesananServisController::class, 'create'])->name('servis.create');
Route::post('/servis/pesan', [PesananServisController::class, 'store'])->name('servis.store');

Route::get('/servis/{pesananServis}/bayar', [PembayaranController::class, 'show'])->name('pembayaran.servis.show');
Route::post('/servis/{pesananServis}/bayar', [PembayaranController::class, 'store'])->name('pembayaran.servis.store');