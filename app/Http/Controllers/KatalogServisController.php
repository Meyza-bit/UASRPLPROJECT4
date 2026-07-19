<?php

namespace App\Http\Controllers;

use App\Models\PesananServis;
use App\Models\PesananServisDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KatalogServisController extends Controller
{
    /**
     * Daftar layanan servis. Ditulis tetap di sini sesuai mockup,
     * jadi nggak perlu tabel khusus. Kode dipakai sebagai penanda tiap layanan.
     */
    public static function daftarLayanan(): array
    {
        return [
            'tune-up' => [
                'nama'      => 'Tune-Up Lengkap',
                'deskripsi' => 'Pemeriksaan menyeluruh, setel rem & gir untuk performa optimal.',
                'harga'     => 50000,
                'mulai'     => false,
            ],
            'ganti-komponen' => [
                'nama'      => 'Ganti Komponen Baru',
                'deskripsi' => 'Penggantian suku cadang berkualitas tinggi sesuai kebutuhan.',
                'harga'     => 15000,
                'mulai'     => true,
            ],
            'ganti-rantai' => [
                'nama'      => 'Ganti Rantai',
                'deskripsi' => 'Pemasangan rantai baru untuk kelancaran gowes Anda.',
                'harga'     => 35000,
                'mulai'     => false,
            ],
            'tambal-ban' => [
                'nama'      => 'Tambal / Ganti Ban',
                'deskripsi' => 'Solusi cepat untuk ban bocor atau aus di perjalanan.',
                'harga'     => 10000,
                'mulai'     => true,
            ],
        ];
    }

    /**
     * Halaman Katalog Servis: daftar layanan + keranjang + pilih jadwal.
     */
    public function index()
    {
        $layanan   = self::daftarLayanan();
        $keranjang = $this->isiKeranjang();
        $total     = collect($keranjang)->sum('harga');

        return view('katalog.servis', compact('layanan', 'keranjang', 'total'));
    }

    /**
     * Tombol "Tambah ke Keranjang".
     */
    public function tambah(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string'],
        ]);

        $layanan = self::daftarLayanan();

        // Pastikan kode layanannya memang ada di daftar
        if (! isset($layanan[$data['kode']])) {
            return back()->withErrors(['keranjang' => 'Layanan tidak ditemukan.']);
        }

        $keranjang = session('servis.keranjang', []);

        // Satu layanan cukup sekali di keranjang
        if (! in_array($data['kode'], $keranjang)) {
            $keranjang[] = $data['kode'];
            session(['servis.keranjang' => $keranjang]);
        }

        return back();
    }

    /**
     * Hapus satu layanan dari keranjang.
     */
    public function hapus(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string'],
        ]);

        $keranjang = session('servis.keranjang', []);
        $keranjang = array_values(array_diff($keranjang, [$data['kode']]));
        session(['servis.keranjang' => $keranjang]);

        return back();
    }

    /**
     * Tombol "Buat Pesanan Servis": keranjang disimpan jadi pesanan.
     * Data disimpan lewat model punya modul servis (PesananServis),
     * jadi menyatu dengan alur pembayaran & admin yang sudah ada.
     */
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'tanggal_jadwal' => ['required', 'date', 'after_or_equal:today'],
            'waktu_jadwal'   => ['required'],
            'catatan'        => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal_jadwal.after_or_equal' => 'Tanggal servis tidak boleh sebelum hari ini.',
        ]);

        $keranjang = $this->isiKeranjang();

        if (empty($keranjang)) {
            return back()->withErrors(['keranjang' => 'Keranjang servis masih kosong.']);
        }

        $total = collect($keranjang)->sum('harga');

        $pesanan = DB::transaction(function () use ($data, $keranjang, $total) {

            $pesanan = PesananServis::create([
                'user_id'          => Auth::id(),
                'tanggal_jadwal'   => $data['tanggal_jadwal'],
                'waktu_jadwal'     => $data['waktu_jadwal'],
                'catatan'          => $data['catatan'] ?? null,
                'biaya_admin'      => 0,
                'total_pembayaran' => $total,
                'status'           => 'proses',
            ]);

            foreach ($keranjang as $item) {
                PesananServisDetail::create([
                    'pesanan_servis_id' => $pesanan->id,
                    'jenis_layanan'     => $item['nama'],
                    'harga_layanan'     => $item['harga'],
                ]);
            }

            return $pesanan;
        });

        session()->forget('servis.keranjang');

        // Lanjut ke pembayaran servis milik modul Kia
        return redirect()->route('pembayaran.servis.show', $pesanan->id);
    }

    // ================= FUNGSI BANTU =================

    /**
     * Ubah isi session keranjang (yang cuma berisi kode) jadi data lengkap.
     */
    private function isiKeranjang(): array
    {
        $kode    = session('servis.keranjang', []);
        $layanan = self::daftarLayanan();

        $hasil = [];

        foreach ($kode as $k) {
            if (isset($layanan[$k])) {
                $hasil[$k] = [
                    'kode'  => $k,
                    'nama'  => $layanan[$k]['nama'],
                    'harga' => $layanan[$k]['harga'],
                ];
            }
        }

        return $hasil;
    }
}