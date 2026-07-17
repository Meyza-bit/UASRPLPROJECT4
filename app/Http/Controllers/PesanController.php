<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Sepeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
{
    /**
     * Halaman Pesan: daftar sepeda + keranjang.
     */
    public function index()
    {
        $sepeda = Sepeda::aktif()->orderBy('kode')->get();

        $jadwal    = $this->ambilJadwal();
        $keranjang = $this->isiKeranjang($jadwal['durasi']);
        $total     = collect($keranjang)->sum('subtotal');

        return view('pesan.sewa', compact('sepeda', 'keranjang', 'jadwal', 'total'));
    }

    /**
     * Tombol "Tambah ke Pesanan".
     */
    public function tambah(Request $request)
    {
        $data = $request->validate([
            'sepeda_id' => ['required', 'exists:sepeda,id'],
            'qty'       => ['required', 'integer', 'min:1'],
        ]);

        $sepeda = Sepeda::findOrFail($data['sepeda_id']);

        $keranjang = session('sewa.keranjang', []);
        $qtyBaru   = ($keranjang[$sepeda->id] ?? 0) + $data['qty'];

        // Nggak boleh pesan lebih banyak dari stok yang ada
        if ($qtyBaru > $sepeda->stok) {
            return back()->withErrors([
                'keranjang' => "Stok {$sepeda->nama} tinggal {$sepeda->stok} unit.",
            ]);
        }

        $keranjang[$sepeda->id] = $qtyBaru;
        session(['sewa.keranjang' => $keranjang]);

        return back();
    }

    /**
     * Hapus satu sepeda dari keranjang.
     */
    public function hapus(Request $request)
    {
        $data = $request->validate([
            'sepeda_id' => ['required'],
        ]);

        $keranjang = session('sewa.keranjang', []);
        unset($keranjang[$data['sepeda_id']]);
        session(['sewa.keranjang' => $keranjang]);

        return back();
    }

    /**
     * Ubah Jadwal Sewa / Waktu / Lama Sewa.
     */
    public function jadwal(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam'     => ['required'],
            'durasi'  => ['required', 'in:1,3,6'],
        ], [
            'tanggal.after_or_equal' => 'Tanggal sewa tidak boleh sebelum hari ini.',
        ]);

        session([
            'sewa.tanggal' => $data['tanggal'],
            'sewa.jam'     => $data['jam'],
            'sewa.durasi'  => (int) $data['durasi'],
        ]);

        return back();
    }

    /**
     * Tombol "Buat Pesanan": keranjang disimpan jadi pesanan beneran.
     */
    public function simpan(Request $request)
    {
        $jadwal    = $this->ambilJadwal();
        $keranjang = $this->isiKeranjang($jadwal['durasi']);

        if (empty($keranjang)) {
            return back()->withErrors(['keranjang' => 'Keranjang masih kosong.']);
        }

        // Cek ulang stok sebelum disimpan. Bisa saja ada orang lain
        // yang memesan sepeda yang sama sementara keranjang ini dibiarkan terbuka.
        foreach ($keranjang as $item) {
            if ($item['qty'] > $item['sepeda']->stok) {
                return back()->withErrors([
                    'keranjang' => "Stok {$item['sepeda']->nama} tinggal {$item['sepeda']->stok} unit.",
                ]);
            }
        }

        // DB::transaction: kalau ada satu langkah yang gagal, semuanya dibatalkan.
        // Jadi nggak ada pesanan setengah jadi atau stok yang kepotong percuma.
        $penyewaan = DB::transaction(function () use ($jadwal, $keranjang) {

            $penyewaan = Penyewaan::create([
                'kode'         => Penyewaan::buatKode(),
                'user_id'      => auth()->id(),
                'tanggal_sewa' => $jadwal['tanggal'],
                'jam_mulai'    => $jadwal['jam'],
                'durasi_jam'   => $jadwal['durasi'],
                'total'        => collect($keranjang)->sum('subtotal'),
                'status'       => 'menunggu_pembayaran',
            ]);

            foreach ($keranjang as $item) {
                $penyewaan->detail()->create([
                    'sepeda_id'    => $item['sepeda']->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['subtotal'],
                ]);

                // Stok berkurang begitu pesanan dibuat
                $item['sepeda']->decrement('stok', $item['qty']);
            }

            return $penyewaan;
        });

        // Keranjang dikosongkan setelah jadi pesanan
        session()->forget('sewa.keranjang');

       return redirect()->route('pembayaran.show', $penyewaan);
    }

    // ================= FUNGSI BANTU =================

    /**
     * Jadwal yang lagi dipilih. Kalau belum pernah diisi, pakai nilai default.
     */
    private function ambilJadwal(): array
    {
        return [
            'tanggal' => session('sewa.tanggal', now()->toDateString()),
            'jam'     => session('sewa.jam', '14:00'),
            'durasi'  => session('sewa.durasi', 1),
        ];
    }

    /**
     * Ubah isi session keranjang jadi data lengkap: sepedanya apa, harganya berapa.
     */
    private function isiKeranjang(int $durasi): array
    {
        $keranjang = session('sewa.keranjang', []);

        if (empty($keranjang)) {
            return [];
        }

        $daftarSepeda = Sepeda::whereIn('id', array_keys($keranjang))->get()->keyBy('id');

        $hasil = [];

        foreach ($keranjang as $sepedaId => $qty) {
            $sepeda = $daftarSepeda->get($sepedaId);

            if (! $sepeda) {
                continue;   // sepedanya sudah dihapus admin
            }

            $harga = $sepeda->hargaDurasi($durasi);

            $hasil[$sepedaId] = [
                'sepeda'   => $sepeda,
                'qty'      => $qty,
                'harga'    => $harga,
                'subtotal' => $harga * $qty,
            ];
        }

        return $hasil;
    }
}