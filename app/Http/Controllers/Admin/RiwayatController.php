<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\PesananServis;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'semua');
        $cari  = $request->get('cari');

        // Ambil semua transaksi sewa, ubah jadi format yang seragam
        $sewa = Penyewaan::with('user')->get()->map(function ($item) {
            return [
                'jenis'   => 'sewa',
                'kode'    => $item->kode,
                'user'    => $item->user->nama_tampil ?? '-',
                'tanggal' => $item->created_at,
                'total'   => $item->total,
                'status'  => $item->label_status,
            ];
        });

        // Ambil semua transaksi servis, format yang sama
        $servis = PesananServis::with('user')->get()->map(function ($item) {
            return [
                'jenis'   => 'servis',
                'kode'    => '#' . $item->id,
                'user'    => $item->user->nama_tampil ?? '-',
                'tanggal' => $item->created_at,
                'total'   => $item->total_pembayaran,
                'status'  => ucfirst($item->status),
            ];
        });

        // Gabung dua-duanya jadi 1 daftar, urutkan dari yang terbaru
        $gabungan = $sewa->concat($servis)->sortByDesc('tanggal')->values();

        // Filter jenis (kalau bukan "semua")
        if ($jenis !== 'semua') {
            $gabungan = $gabungan->where('jenis', $jenis)->values();
        }

        // Search berdasarkan nama user
        if ($cari) {
            $gabungan = $gabungan->filter(
                fn ($row) => str_contains(strtolower($row['user']), strtolower($cari))
            )->values();
        }

        // Pagination manual (soalnya datanya gabungan dari 2 tabel berbeda,
        // bukan hasil query tunggal yang bisa langsung dipaginate otomatis)
        $perHalaman = 15;
        $halamanSekarang = LengthAwarePaginator::resolveCurrentPage();

        $riwayat = new LengthAwarePaginator(
            $gabungan->slice(($halamanSekarang - 1) * $perHalaman, $perHalaman)->values(),
            $gabungan->count(),
            $perHalaman,
            $halamanSekarang,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.riwayat.index', compact('riwayat', 'jenis', 'cari'));
    }
}