<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RiwayatController extends Controller
{
    /**
     * Daftar riwayat penyewaan milik user yang sedang login.
     */
    public function index()
    {
        $riwayat = Penyewaan::with('detail.sepeda')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('riwayat.index', compact('riwayat'));
    }

    /**
     * Halaman Status Pesanan (nota satu transaksi).
     */
    public function show(Penyewaan $penyewaan)
    {
        // Tanpa pengecekan ini, orang bisa melihat nota orang lain
        // hanya dengan mengganti angka di URL.
        if ($penyewaan->user_id !== auth()->id()) {
            throw new NotFoundHttpException();
        }

        $penyewaan->load('detail.sepeda', 'user');

        $pembayaran = Pembayaran::sewa()
            ->where('pesanan_id', $penyewaan->id)
            ->first();

        return view('riwayat.show', compact('penyewaan', 'pembayaran'));
    }
}