<?php

namespace App\Http\Controllers;

use App\Models\Sepeda;

class BerandaController extends Controller
{
    public function index()
    {
        // Angka statistik diambil dari database, bukan ditulis manual.
        // Jadi kalau admin nambah unit, angkanya ikut berubah sendiri.
        $totalUnit = Sepeda::aktif()->sum('stok');

        return view('beranda', compact('totalUnit'));
    }
}