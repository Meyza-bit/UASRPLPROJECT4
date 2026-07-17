<?php

namespace App\Http\Controllers;

use App\Models\Sepeda;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari URL, misal: /katalog?kategori=premium
        // Kalau nggak ada, defaultnya "semua"
        $kategori = $request->query('kategori', 'semua');

        $sepeda = Sepeda::aktif()                 // sembunyikan unit yang dinonaktifkan admin
            ->kategori($kategori)                 // filter Semua / Premium / Standar
            ->orderBy('kode')
            ->paginate(8)                         // 8 per halaman, sesuai mockup
            ->withQueryString();                  // biar filter nggak hilang pas pindah halaman

        return view('katalog.index', compact('sepeda', 'kategori'));
    }
}