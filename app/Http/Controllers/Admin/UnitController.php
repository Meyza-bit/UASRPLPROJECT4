<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sepeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    // Grid kartu unit + search & filter kategori
    public function index(Request $request)
    {
        $query = Sepeda::query();

        // Search berdasarkan nama atau kode
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('kode', 'like', '%' . $request->cari . '%');
            });
        }

        // Filter kategori (premium / standar / semua)
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        $unit = $query->latest()->paginate(8)->withQueryString();

        return view('admin.unit.index', compact('unit'));
    }

    // Simpan unit baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:20|unique:sepeda,kode',
            'nama'          => 'required|string|max:100',
            'tipe'          => 'required|string|max:50',
            'kategori'      => 'required|in:premium,standar',
            'stok'          => 'required|integer|min:0',
            'harga_per_jam' => 'required|numeric|min:0',
            'harga_3jam'    => 'required|numeric|min:0',
            'harga_6jam'    => 'required|numeric|min:0',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('unit-sepeda', 'public');
        }

        $data['is_aktif'] = true; // unit baru default aktif

        Sepeda::create($data);

        return redirect()->route('admin.unit.index')->with('sukses', 'Unit baru berhasil ditambahkan.');
    }

    // Update unit yang ada
    public function update(Request $request, Sepeda $sepedum)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:20|unique:sepeda,kode,' . $sepedum->id,
            'nama'          => 'required|string|max:100',
            'tipe'          => 'required|string|max:50',
            'kategori'      => 'required|in:premium,standar',
            'stok'          => 'required|integer|min:0',
            'harga_per_jam' => 'required|numeric|min:0',
            'harga_3jam'    => 'required|numeric|min:0',
            'harga_6jam'    => 'required|numeric|min:0',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama biar nggak numpuk
            if ($sepedum->foto) {
                Storage::disk('public')->delete($sepedum->foto);
            }
            $data['foto'] = $request->file('foto')->store('unit-sepeda', 'public');
        }

        $sepedum->update($data);

        return redirect()->route('admin.unit.index')->with('sukses', 'Unit berhasil diperbarui.');
    }

    // Hapus unit
    public function destroy(Sepeda $sepedum)
    {
        if ($sepedum->foto) {
            Storage::disk('public')->delete($sepedum->foto);
        }

        $sepedum->delete();

        return redirect()->route('admin.unit.index')->with('sukses', 'Unit berhasil dihapus.');
    }

    // Toggle aktif / non-aktif
    public function toggleAktif(Sepeda $sepedum)
    {
        $sepedum->update(['is_aktif' => ! $sepedum->is_aktif]);

        return back()->with('sukses', 'Status unit berhasil diubah.');
    }
}