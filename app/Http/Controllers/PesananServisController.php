<?php

namespace App\Http\Controllers;

use App\Models\PesananServis;
use App\Models\PesananServisDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananServisController extends Controller
{
    // Menampilkan form pesan servis
    public function create()
    {
        return view('servis.create');
    }

    // Menyimpan pesanan servis baru (bisa lebih dari 1 layanan)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_jadwal'          => 'required|date',
            'waktu_jadwal'            => 'nullable',
            'catatan'                 => 'nullable|string',
            'layanan'                 => 'required|array|min:1',
            'layanan.*.jenis_layanan' => 'required|string',
            'layanan.*.harga_layanan' => 'required|numeric',
        ]);

        // Cek jam operasional servis
        $error = $this->cekJamServis($request->tanggal_jadwal, $request->waktu_jadwal);
        if ($error) {
            return back()->withErrors(['waktu_jadwal' => $error])->withInput();
        }
        // Hitung total dari semua layanan yang dipilih
        $totalLayanan = collect($request->layanan)->sum('harga_layanan');
        $biayaAdmin   = 0; // sesuaikan kalau ada aturan biaya admin
        $totalBayar   = $totalLayanan + $biayaAdmin;

        // Simpan header + detail dalam 1 transaksi database
        // (biar kalau salah satu gagal, semuanya batal — nggak ada data setengah jalan)
        // "return" di dalam DB::transaction() otomatis jadi nilai balik transaction-nya,
        // jadi $pesanan bisa dipakai lagi di luar closure ini.
        $pesanan = DB::transaction(function () use ($request, $biayaAdmin, $totalBayar) {
            $pesanan = PesananServis::create([
                'user_id'          => Auth::id(),
                'tanggal_jadwal'   => $request->tanggal_jadwal,
                'waktu_jadwal'     => $request->waktu_jadwal,
                'catatan'          => $request->catatan,
                'biaya_admin'      => $biayaAdmin,
                'total_pembayaran' => $totalBayar,
                'status'           => 'proses',
            ]);

            foreach ($request->layanan as $item) {
                PesananServisDetail::create([
                    'pesanan_servis_id' => $pesanan->id,
                    'jenis_layanan'     => $item['jenis_layanan'],
                    'harga_layanan'     => $item['harga_layanan'],
                ]);
            }

            return $pesanan;
        });

        return redirect()->route('pembayaran.servis.show', $pesanan->id)
            ->with('success', 'Pesanan servis berhasil dibuat!');
    }

    /**
     * Cek apakah jam servis masih dalam jam operasional.
     * Weekday buka 08.00, weekend buka 06.00. Paling malam 20.00.
     */
    private function cekJamServis(string $tanggal, ?string $jam): ?string
    {
        if (! $jam) {
            return null;
        }

        $hari    = \Carbon\Carbon::parse($tanggal);
        $jamBuka = $hari->isWeekend() ? '06:00' : '08:00';

        $menit     = $this->keMenit($jam);
        $menitBuka = $this->keMenit($jamBuka);

        if ($menit < $menitBuka) {
            $labelHari = $hari->isWeekend() ? 'akhir pekan' : 'hari kerja';
            return "Servis {$labelHari} baru buka jam " . substr($jamBuka, 0, 5) . '.';
        }

        if ($menit > $this->keMenit('20:00')) {
            return 'Pemesanan servis terakhir jam 20.00.';
        }

        return null;
    }

    /**
     * Ubah "20:00" jadi jumlah menit (1200).
     */
    private function keMenit(string $jam): int
    {
        [$j, $m] = explode(':', substr($jam, 0, 5));
        return ((int) $j) * 60 + (int) $m;
    }
}