<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penyewaan;
use App\Models\PesananServis;
use Illuminate\Http\Request;

class PesananMasukController extends Controller
{
    // Menampilkan daftar pesanan masuk (tab sewa / servis)
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'sewa');

        if ($tab === 'servis') {
            $antrean = PesananServis::with(['user', 'detail', 'pembayaran'])
                ->whereHas('pembayaran', fn ($q) => $q->where('status', 'menunggu')->whereNotNull('bukti_bayar'))
                ->latest()
                ->get();

            $berjalan = PesananServis::where('status', 'proses')
                ->whereHas('pembayaran', fn ($q) => $q->where('status', 'diverifikasi'))
                ->with('user')
                ->latest()
                ->get();
        } else {
            $antrean = Penyewaan::with(['user', 'detail.sepeda', 'pembayaran'])
                ->whereHas('pembayaran', fn ($q) => $q->where('status', 'menunggu')->whereNotNull('bukti_bayar'))
                ->latest()
                ->get();

            $berjalan = Penyewaan::where('status', 'aktif')
                ->with('user')
                ->latest()
                ->get();
        }

        return view('admin.pesanan-masuk.index', compact('tab', 'antrean', 'berjalan'));
    }

    // Approve pembayaran sewa
    public function approveSewa(Penyewaan $penyewaan)
    {
        $pembayaran = Pembayaran::sewa()->where('pesanan_id', $penyewaan->id)->firstOrFail();

        $pembayaran->update(['status' => 'diverifikasi', 'diverifikasi_at' => now()]);
        $penyewaan->update(['status' => 'aktif']);

        return back()->with('sukses', 'Pembayaran sewa berhasil dikonfirmasi.');
    }

    // Reject pembayaran sewa
    public function rejectSewa(Penyewaan $penyewaan)
    {
        $pembayaran = Pembayaran::sewa()->where('pesanan_id', $penyewaan->id)->firstOrFail();

        $pembayaran->update(['status' => 'ditolak']);
        $penyewaan->update(['status' => 'menunggu_pembayaran']);

        return back()->with('sukses', 'Pembayaran sewa ditolak.');
    }

    // Tandai sewa selesai
    public function selesaiSewa(Penyewaan $penyewaan)
    {
        $penyewaan->update(['status' => 'selesai']);

        return back()->with('sukses', "Pesanan {$penyewaan->kode} ditandai selesai.");
    }

    // Approve pembayaran servis
    public function approveServis(PesananServis $pesananServis)
    {
        $pembayaran = Pembayaran::servis()->where('pesanan_id', $pesananServis->id)->firstOrFail();

        $pembayaran->update(['status' => 'diverifikasi', 'diverifikasi_at' => now()]);
        $pesananServis->update(['status' => 'proses']);

        return back()->with('sukses', 'Pembayaran servis berhasil dikonfirmasi.');
    }

    // Reject pembayaran servis
    public function rejectServis(PesananServis $pesananServis)
    {
        $pembayaran = Pembayaran::servis()->where('pesanan_id', $pesananServis->id)->firstOrFail();

        $pembayaran->update(['status' => 'ditolak']);

        return back()->with('sukses', 'Pembayaran servis ditolak.');
    }

    // Tandai servis selesai
    public function selesaiServis(PesananServis $pesananServis)
    {
        $pesananServis->update(['status' => 'selesai']);

        return back()->with('sukses', 'Pesanan servis ditandai selesai.');
    }
} 