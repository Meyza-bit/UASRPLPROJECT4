<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PenyewaanDetail;
use App\Models\PesananServis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    // Bangun data laporan bulanan, dipakai bareng buat tampilan & export
    private function bangunLaporan(int $jumlahBulan = 6): array
    {
        $baris = [];

        for ($i = $jumlahBulan - 1; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $awal = $bulan->copy()->startOfMonth();
            $akhir = $bulan->copy()->endOfMonth();

            $pendapatan = Pembayaran::where('status', 'diverifikasi')
                ->whereBetween('diverifikasi_at', [$awal, $akhir])
                ->sum('jumlah');

            $unitTersewa = PenyewaanDetail::whereHas('penyewaan', function ($q) use ($awal, $akhir) {
                $q->whereIn('status', ['aktif', 'selesai'])
                  ->whereBetween('tanggal_sewa', [$awal, $akhir]);
            })->sum('qty');

            $servisSelesai = PesananServis::where('status', 'selesai')
                ->whereBetween('updated_at', [$awal, $akhir])
                ->count();

            $baris[] = [
                'bulan'          => $bulan->translatedFormat('F Y'),
                'pendapatan'     => $pendapatan,
                'unit_tersewa'   => $unitTersewa,
                'servis_selesai' => $servisSelesai,
            ];
        }

        return $baris;
    }

    // Halaman laporan
    public function index()
    {
        $laporanBulanan = $this->bangunLaporan(6);

        $totalPendapatan = collect($laporanBulanan)->sum('pendapatan');
        $totalUnitTersewa = collect($laporanBulanan)->sum('unit_tersewa');
        $totalServisSelesai = collect($laporanBulanan)->sum('servis_selesai');

        return view('admin.laporan.index', compact(
            'laporanBulanan', 'totalPendapatan', 'totalUnitTersewa', 'totalServisSelesai'
        ));
    }

    // Export CSV
    public function export(): StreamedResponse
    {
        $laporanBulanan = $this->bangunLaporan(12);

        $namaFile = 'laporan-culture-bike-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$namaFile\"",
        ];

        $callback = function () use ($laporanBulanan) {
            $file = fopen('php://output', 'w');

            // Header kolom
            fputcsv($file, ['Bulan', 'Pendapatan', 'Unit Tersewa', 'Servis Selesai']);

            foreach ($laporanBulanan as $baris) {
                fputcsv($file, [
                    $baris['bulan'],
                    $baris['pendapatan'],
                    $baris['unit_tersewa'],
                    $baris['servis_selesai'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}