<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananServisController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\PesananMasukController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\KatalogServisController;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/servis', [KatalogServisController::class, 'index'])->name('katalog.servis.index');

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

// Halaman pesan, profil, pembayaran, riwayat, servis (cuma buat yang SUDAH login)
Route::middleware('auth')->group(function () {
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::post('/pesan/tambah', [PesanController::class, 'tambah'])->name('pesan.tambah');
    Route::post('/pesan/hapus', [PesanController::class, 'hapus'])->name('pesan.hapus');
    Route::post('/pesan/jadwal', [PesanController::class, 'jadwal'])->name('pesan.jadwal');
    Route::post('/pesan/simpan', [PesanController::class, 'simpan'])->name('pesan.simpan');
    Route::post('/katalog/servis/tambah', [KatalogServisController::class, 'tambah'])->name('katalog.servis.tambah');
    Route::post('/katalog/servis/hapus', [KatalogServisController::class, 'hapus'])->name('katalog.servis.hapus');
    Route::post('/katalog/servis/simpan', [KatalogServisController::class, 'simpan'])->name('katalog.servis.simpan');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password', [ProfilController::class, 'gantiPassword'])->name('profil.password');

    Route::get('/pembayaran/{penyewaan}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{penyewaan}/upload', [PembayaranController::class, 'upload'])->name('pembayaran.upload');
    Route::get('/pembayaran/{penyewaan}/berhasil', [PembayaranController::class, 'berhasil'])->name('pembayaran.berhasil');
    Route::post('/pembayaran/{penyewaan}/batal', [PembayaranController::class, 'batal'])->name('pembayaran.batal');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{penyewaan}', [RiwayatController::class, 'show'])->name('riwayat.show');

    // Rute punya Kia — modul servis
    Route::get('/servis/pesan', [PesananServisController::class, 'create'])->name('servis.create');
    Route::post('/servis/pesan', [PesananServisController::class, 'store'])->name('servis.store');

    Route::get('/servis/{pesananServis}/bayar', [PembayaranController::class, 'showServis'])->name('pembayaran.servis.show');
    Route::post('/servis/{pesananServis}/bayar', [PembayaranController::class, 'uploadServis'])->name('pembayaran.servis.store');
    Route::get('/servis/{pesananServis}/berhasil', [PembayaranController::class, 'berhasilServis'])->name('pembayaran.servis.berhasil');
});

// Halaman admin (cuma admin yang bisa akses) — punya Kia
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
    Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
    Route::put('/unit/{sepedum}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/{sepedum}', [UnitController::class, 'destroy'])->name('unit.destroy');
    Route::patch('/unit/{sepedum}/toggle', [UnitController::class, 'toggleAktif'])->name('unit.toggle');

    Route::get('/pesanan-masuk', [PesananMasukController::class, 'index'])->name('pesanan-masuk.index');

    Route::post('/pesanan-masuk/sewa/{penyewaan}/approve', [PesananMasukController::class, 'approveSewa'])->name('pesanan-masuk.sewa.approve');
    Route::post('/pesanan-masuk/sewa/{penyewaan}/reject', [PesananMasukController::class, 'rejectSewa'])->name('pesanan-masuk.sewa.reject');
    Route::post('/pesanan-masuk/sewa/{penyewaan}/selesai', [PesananMasukController::class, 'selesaiSewa'])->name('pesanan-masuk.sewa.selesai');

    Route::post('/pesanan-masuk/servis/{pesananServis}/approve', [PesananMasukController::class, 'approveServis'])->name('pesanan-masuk.servis.approve');
    Route::post('/pesanan-masuk/servis/{pesananServis}/reject', [PesananMasukController::class, 'rejectServis'])->name('pesanan-masuk.servis.reject');
    Route::post('/pesanan-masuk/servis/{pesananServis}/selesai', [PesananMasukController::class, 'selesaiServis'])->name('pesanan-masuk.servis.selesai');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{penyewaan}', [PengembalianController::class, 'store'])->name('pengembalian.store');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/{pengguna}', [PenggunaController::class, 'show'])->name('pengguna.show');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});