<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\PesananServis;
use App\Models\Sepeda;

class DashboardController extends Controller
{
    public function index()
    {
        // Total unit = jumlah stok semua sepeda (bukan jumlah baris),
        // sesuai catatan Mey: Sepeda::sum('stok'), bukan count()
        $totalUnit = Sepeda::sum('stok');

        // Sewa aktif = penyewaan yang statusnya sedang berjalan
        $sewaAktif = Penyewaan::where('status', 'aktif')->count();

        // Servis masuk = pesanan servis yang masih diproses
        $servisMasuk = PesananServis::where('status', 'proses')->count();

        // Total pendapatan = jumlah dari semua pembayaran yang sudah diverifikasi
        // (baik dari sewa maupun servis)
        $totalPendapatan = \App\Models\Pembayaran::where('status', 'diverifikasi')->sum('jumlah');

        // Pesanan terbaru: gabungan dari sewa & servis, diurutkan dari yang terbaru
        $pesananSewaTerbaru = Penyewaan::with('user')->latest()->take(5)->get()
            ->map(fn ($p) => [
                'nama'    => $p->user->nama_tampil ?? '-',
                'jenis'   => 'Sewa',
                'tanggal' => $p->created_at,
                'status'  => $p->status,
            ]);

        $pesananServisTerbaru = PesananServis::with('user')->latest()->take(5)->get()
            ->map(fn ($p) => [
                'nama'    => $p->user->nama_tampil ?? '-',
                'jenis'   => 'Servis',
                'tanggal' => $p->created_at,
                'status'  => $p->status,
            ]);

        $pesananTerbaru = $pesananSewaTerbaru
            ->concat($pesananServisTerbaru)
            ->sortByDesc('tanggal')
            ->take(5);

        // Status unit: tersedia vs disewa
        $unitTersedia = Sepeda::sum('stok');
        $unitDisewa   = \App\Models\PenyewaanDetail::whereHas('penyewaan', function ($q) {
            $q->where('status', 'aktif');
        })->sum('qty');

        return view('admin.dashboard', compact(
            'totalUnit',
            'sewaAktif',
            'servisMasuk',
            'totalPendapatan',
            'pesananTerbaru',
            'unitTersedia',
            'unitDisewa'
        ));
    }
}