<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PengembalianController extends Controller
{
    // List sepeda yang lagi disewa (status aktif)
    public function index()
    {
        $antrean = Penyewaan::where('status', 'aktif')
            ->with(['user', 'detail.sepeda'])
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('antrean'));
    }

    // Proses form pengembalian
    public function store(Request $request, Penyewaan $penyewaan)
    {
        $request->validate([
            'cek_body_cat'    => 'nullable|boolean',
            'cek_rem'         => 'nullable|boolean',
            'cek_ban'         => 'nullable|boolean',
            'cek_kelengkapan' => 'nullable|boolean',
            'catatan'         => 'nullable|string',
        ]);

        // Hitung keterlambatan: bandingkan sekarang vs jadwal seharusnya selesai
        $waktuSeharusnyaKembali = Carbon::parse($penyewaan->tanggal_sewa->format('Y-m-d') . ' ' . $penyewaan->jam_mulai)
            ->addHours($penyewaan->durasi_jam);

        $jamTelat = now()->greaterThan($waktuSeharusnyaKembali)
            ? ceil($waktuSeharusnyaKembali->diffInMinutes(now()) / 60)
            : 0;

        $dendaPerJam = 5000;
        $denda = $jamTelat * $dendaPerJam;

        Pengembalian::create([
            'penyewaan_id'    => $penyewaan->id,
            'cek_body_cat'    => $request->boolean('cek_body_cat'),
            'cek_rem'         => $request->boolean('cek_rem'),
            'cek_ban'         => $request->boolean('cek_ban'),
            'cek_kelengkapan' => $request->boolean('cek_kelengkapan'),
            'catatan'         => $request->catatan,
            'jam_telat'       => $jamTelat,
            'denda'           => $denda,
            'diproses_oleh'   => auth()->id(),
        ]);

        // Kembalikan stok tiap sepeda yang disewa
        foreach ($penyewaan->detail as $item) {
            $item->sepeda->increment('stok', $item->qty);
        }

        $penyewaan->update(['status' => 'selesai']);

        return back()->with('sukses', "Pengembalian {$penyewaan->kode} berhasil diproses" . ($denda > 0 ? ", denda Rp" . number_format($denda, 0, ',', '.') : "") . ".");
    }
}