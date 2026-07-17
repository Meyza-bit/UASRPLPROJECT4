<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PembayaranController extends Controller
{
    /**
     * Halaman pembayaran sebuah pesanan sewa.
     */
    public function show(Penyewaan $penyewaan)
    {
        $this->pastikanMilikSendiri($penyewaan);

        // Kalau sudah lunas, langsung arahkan ke halaman berhasil
        if (in_array($penyewaan->status, ['aktif', 'selesai'])) {
            return redirect()->route('pembayaran.berhasil', $penyewaan);
        }

        $penyewaan->load('detail.sepeda');

        // Data pembayaran dibuat sekali saja, sekalian dengan batas waktunya.
        $pembayaran = Pembayaran::firstOrCreate(
            [
                'jenis_pesanan' => 'sewa',
                'pesanan_id'    => $penyewaan->id,
            ],
            [
                'metode_bayar' => 'QRIS / Transfer Bank',
                'jumlah'       => $penyewaan->total,
                'status'       => 'menunggu',
                'batas_waktu'  => $penyewaan->created_at->addDay(),   // 24 jam sejak pesanan dibuat
            ]
        );

        return view('pembayaran.index', compact('penyewaan', 'pembayaran'));
    }

    /**
     * Customer mengunggah bukti transfer.
     */
    public function upload(Request $request, Penyewaan $penyewaan)
    {
        $this->pastikanMilikSendiri($penyewaan);

        $request->validate([
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_bayar.mimes'    => 'File harus berformat JPG, PNG, atau PDF.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $pembayaran = Pembayaran::sewa()->where('pesanan_id', $penyewaan->id)->firstOrFail();

        if ($pembayaran->kadaluarsa) {
            return back()->withErrors(['bukti_bayar' => 'Batas waktu pembayaran sudah lewat.']);
        }

        DB::transaction(function () use ($request, $pembayaran, $penyewaan) {

            // Kalau pernah upload sebelumnya, file lama dihapus supaya nggak menumpuk
            if ($pembayaran->bukti_bayar) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

            $pembayaran->update([
                'bukti_bayar' => $path,
                'status'      => 'menunggu',
            ]);

            // Giliran admin yang memeriksa
            $penyewaan->update(['status' => 'menunggu_verifikasi']);
        });

        return redirect()
            ->route('pembayaran.berhasil', $penyewaan)
            ->with('sukses', 'Bukti pembayaran berhasil diunggah.');
    }

    /**
     * Halaman "Pembayaran Berhasil".
     */
    public function berhasil(Penyewaan $penyewaan)
    {
        $this->pastikanMilikSendiri($penyewaan);

        $pembayaran = Pembayaran::sewa()->where('pesanan_id', $penyewaan->id)->firstOrFail();

        return view('pembayaran.berhasil', compact('penyewaan', 'pembayaran'));
    }

    /**
     * Tombol "Batalkan Pesanan".
     */
    public function batal(Penyewaan $penyewaan)
    {
        $this->pastikanMilikSendiri($penyewaan);

        if (! in_array($penyewaan->status, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
            return back()->withErrors(['pesanan' => 'Pesanan ini sudah tidak bisa dibatalkan.']);
        }

        DB::transaction(function () use ($penyewaan) {

            // Stok dikembalikan supaya bisa disewa orang lain
            foreach ($penyewaan->detail as $item) {
                $item->sepeda->increment('stok', $item->qty);
            }

            $penyewaan->update(['status' => 'batal']);

            Pembayaran::sewa()->where('pesanan_id', $penyewaan->id)->delete();
        });

        return redirect()
            ->route('pesan.index')
            ->with('sukses', "Pesanan {$penyewaan->kode} dibatalkan.");
    }

    /**
     * Pastikan pesanan ini memang milik user yang sedang login.
     * Tanpa ini, orang bisa membuka pembayaran orang lain hanya dengan mengubah angka di URL.
     */
    private function pastikanMilikSendiri(Penyewaan $penyewaan): void
    {
        if ($penyewaan->user_id !== auth()->id()) {
            throw new NotFoundHttpException();
        }
    }
}