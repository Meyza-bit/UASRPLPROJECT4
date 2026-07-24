<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    /**
     * Daftar riwayat penyewaan milik user yang sedang login.
     */
    public function index()
    {
        // Bersihkan dulu pesanan yang batas bayarnya sudah lewat
        $this->bersihkanPesananKadaluarsa();

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

    /**
     * Batalkan semua pesanan milik user ini yang batas bayarnya sudah lewat.
     * Stok dikembalikan supaya bisa disewa orang lain.
     */
    private function bersihkanPesananKadaluarsa(): void
    {
        $pesanan = Penyewaan::with('detail.sepeda')
            ->where('user_id', auth()->id())
            ->where('status', 'menunggu_pembayaran')
            ->get();

        foreach ($pesanan as $p) {
            $pembayaran = Pembayaran::sewa()->where('pesanan_id', $p->id)->first();

            if (! $pembayaran || ! $pembayaran->kadaluarsa) {
                continue;
            }

            DB::transaction(function () use ($p, $pembayaran) {
                foreach ($p->detail as $item) {
                    $item->sepeda->increment('stok', $item->qty);
                }

                $p->update(['status' => 'batal']);
                $pembayaran->delete();
            });
        }
    }
}