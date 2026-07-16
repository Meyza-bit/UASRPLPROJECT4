<?php

namespace App\Http\Controllers;

use App\Models\PesananServis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // Menampilkan halaman pembayaran untuk 1 pesanan servis tertentu
    public function show(PesananServis $pesananServis)
    {
        // load relasi detail (daftar layanan) biar bisa ditampilin di ringkasan
        $pesananServis->load('detail');

        return view('pembayaran.servis', [
            'pesanan' => $pesananServis,
        ]);
    }

    // Menyimpan bukti pembayaran yang diupload customer
    public function store(Request $request, PesananServis $pesananServis)
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // maks 5MB
        ]);

        // Simpan file ke storage/app/public/bukti-pembayaran
        $path = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');

        Pembayaran::create([
            'id_pesanan'    => $pesananServis->id,
            'jenis_pesanan' => 'servis',
            'metode_bayar'  => 'QRIS',
            'jumlah'        => $pesananServis->total_pembayaran,
            'bukti_bayar'   => $path,
            'status'        => 'menunggu', // nunggu diverifikasi admin
        ]);

        return redirect()
            ->route('pembayaran.servis.show', $pesananServis->id)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}