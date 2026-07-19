<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    // Tabel daftar user + search
    public function index(Request $request)
    {
        $query = User::where('role', 'pelanggan');

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cari . '%')
                  ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        $pengguna = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    // Detail 1 user + riwayat transaksinya (sewa & servis)
    public function show(User $pengguna)
    {
        $riwayatSewa = $pengguna->penyewaan()->latest()->get();
        $riwayatServis = $pengguna->pesananServis()->latest()->get();

        return view('admin.pengguna.show', compact('pengguna', 'riwayatSewa', 'riwayatServis'));
    }
}