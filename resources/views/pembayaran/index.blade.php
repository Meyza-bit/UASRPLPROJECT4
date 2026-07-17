@extends('layouts.app')

@section('title', 'Pembayaran - Culture Bike')

@section('content')
<div class="max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold text-[#7B1E1E] mb-6">Pembayaran</h1>

    @error('bukti_bayar')
        <div class="mb-5 bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">{{ $message }}</div>
    @enderror

    @error('pesanan')
        <div class="mb-5 bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">{{ $message }}</div>
    @enderror

    <div class="grid lg:grid-cols-2 gap-6 items-start">

        {{-- ================= KIRI: ringkasan ================= --}}
        <section class="bg-white border border-[#EFE6D2] rounded-2xl p-7">
            <h2 class="text-lg font-bold text-[#7B1E1E]">Ringkasan Pesanan</h2>

            {{-- Daftar sepeda yang dipesan --}}
            <div class="mt-6 space-y-5">
                @foreach ($penyewaan->detail as $item)
                    <div class="flex gap-4">
                        <div class="w-24 h-16 rounded-lg bg-[#EDE4D2] shrink-0 flex items-center justify-center overflow-hidden">
                            @if ($item->sepeda->foto)
                                <img src="{{ asset('storage/' . $item->sepeda->foto) }}" alt="{{ $item->sepeda->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-[#B9A88F] text-[9px] text-center px-1">Belum ada foto</span>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-[#2B1E1E]">Sewa Sepeda</p>
                            <p class="text-[#7B1E1E] font-semibold truncate">
                                {{ $item->sepeda->nama }}
                                @if ($item->qty > 1)
                                    <span class="text-[#A89478] font-normal">× {{ $item->qty }}</span>
                                @endif
                            </p>

                            <div class="mt-1.5 flex flex-wrap gap-4 text-xs text-[#5C4A3A]">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                    {{ $penyewaan->tanggal_sewa->translatedFormat('j F Y') }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $penyewaan->durasi_jam }} Jam
                                </span>
                            </div>
                        </div>

                        <p class="text-[#2B1E1E] font-semibold shrink-0">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Rincian biaya --}}
            <div class="mt-8 pt-5 border-t border-[#EFE6D2] space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#7B1E1E]">Subtotal Sewa</span>
                    <span class="text-[#5C4A3A]">Rp {{ number_format($penyewaan->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7B1E1E]">Biaya Admin</span>
                    <span class="text-[#5C4A3A]">Rp 0</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-[#EFE6D2] flex justify-between items-center">
                <span class="text-lg font-bold text-[#7B1E1E]">Total Pembayaran</span>
                <span class="text-xl font-bold text-[#2B1E1E]">
                    Rp {{ number_format($penyewaan->total, 0, ',', '.') }}
                </span>
            </div>
        </section>

        {{-- ================= KANAN: pembayaran ================= --}}
        <section class="bg-white border border-[#EFE6D2] rounded-2xl p-7 shadow-sm">
            <h2 class="text-lg font-bold text-[#7B1E1E]">Pembayaran</h2>

            {{-- QRIS --}}
            <div class="mt-6 flex flex-col items-center">
                <div class="w-48 h-48 bg-[#F5F0E4] rounded-lg flex items-center justify-center">
                    {{-- Ganti dengan QRIS asli:
                         <img src="{{ asset('images/qris.png') }}" alt="QRIS Culture Bike" class="w-full h-full object-contain"> --}}
                    <span class="text-[#B9A88F] text-xs text-center px-4">Kode QRIS<br>belum dipasang</span>
                </div>
                <p class="mt-3 text-xs text-[#8A7B6B]">Scan QRIS untuk pembayaran instan</p>
            </div>

            {{-- Hitung mundur --}}
            <div class="mt-5 bg-[#FBE3E3] rounded-lg px-4 py-3 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-[#8A1F1F]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-[#8A1F1F] font-mono">
                    Selesaikan pembayaran dalam
                    <span id="hitung-mundur" data-sisa="{{ $pembayaran->sisa_detik }}">--:--:--</span>
                </span>
            </div>

            {{-- Upload bukti --}}
            <form method="POST" action="{{ route('pembayaran.upload', $penyewaan) }}"
                  enctype="multipart/form-data" class="mt-6">
                @csrf

                <p class="text-sm font-medium text-[#2B1E1E] mb-2">Upload Bukti Pembayaran</p>

                <label for="bukti_bayar"
                       class="block border-2 border-dashed border-[#E5A82E] rounded-xl bg-[#FCF2DA]
                              px-6 py-8 text-center cursor-pointer hover:bg-[#F9EBC8] transition">
                    <svg class="w-9 h-9 mx-auto text-[#7B1E1E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                    </svg>
                    <p class="mt-2 text-sm font-semibold text-[#2B1E1E]">Klik atau seret file ke sini</p>
                    <p class="text-xs text-[#8A7B6B]">Mendukung JPG, PNG, PDF (Maks. 5MB)</p>
                    <p id="nama-file" class="mt-2 text-xs text-[#7B1E1E] font-medium"></p>

                    <input type="file" name="bukti_bayar" id="bukti_bayar"
                           accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                </label>

                {{-- Bukti yang sudah pernah diunggah --}}
                @if ($pembayaran->bukti_bayar)
                    <p class="mt-3 text-xs text-[#1E6B45]">
                        Bukti sudah diunggah.
                        <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank" class="underline">
                            Lihat file
                        </a>
                        — unggah lagi kalau mau mengganti.
                    </p>
                @endif

                <button type="submit"
                        class="mt-5 w-full bg-[#7B1E1E] hover:bg-[#621818] text-white font-medium rounded-xl py-3 transition">
                    Kirim Bukti Pembayaran
                </button>
            </form>

            {{-- Instruksi --}}
            <div class="mt-7 pt-6 border-t border-[#EFE6D2]">
                <p class="text-sm font-medium text-[#2B1E1E]">Instruksi Pembayaran</p>

                <ol class="mt-3 space-y-2.5">
                    @foreach ([
                        'Scan QRIS atau transfer bank.',
                        'Bayar sebesar Rp ' . number_format($penyewaan->total, 0, ',', '.') . '.',
                        'Upload bukti pembayaran pada area di atas.',
                        'Admin akan memverifikasi pembayaran Anda.',
                        'Pesanan diproses setelah pembayaran dikonfirmasi.',
                    ] as $i => $langkah)
                        <li class="flex gap-3 text-xs text-[#5C4A3A]">
                            <span class="w-5 h-5 rounded-full bg-[#DCF3E5] text-[#1E6B45] font-semibold
                                         flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                            {{ $langkah }}
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

    </div>

    {{-- ================= TOMBOL BAWAH ================= --}}
    <div class="mt-8 flex flex-col sm:flex-row justify-end gap-4">
        <form method="POST" action="{{ route('pembayaran.batal', $penyewaan) }}"
              onsubmit="return confirm('Batalkan pesanan {{ $penyewaan->kode }}? Sepeda akan dikembalikan ke katalog.')">
            @csrf
            <button type="submit"
                    class="w-full sm:w-auto border border-[#E3D4B0] text-[#5C4A3A] hover:bg-[#FCF2DA]
                           font-medium rounded-xl px-10 py-3 transition">
                Batalkan Pesanan
            </button>
        </form>

        <a href="{{ route('katalog.index') }}"
           class="w-full sm:w-auto text-center bg-[#7B1E1E] hover:bg-[#621818] text-white
                  font-medium rounded-xl px-10 py-3 transition">
            Sewa Lagi
        </a>
    </div>

</div>

<script>
    // Hitung mundur batas waktu pembayaran
    const kotak = document.getElementById('hitung-mundur');
    let sisa    = parseInt(kotak.dataset.sisa);

    function dua(n) {
        return String(n).padStart(2, '0');
    }

    function perbarui() {
        if (sisa <= 0) {
            kotak.textContent = '00:00:00 — waktu habis';
            return;
        }

        const jam   = Math.floor(sisa / 3600);
        const menit = Math.floor((sisa % 3600) / 60);
        const detik = sisa % 60;

        kotak.textContent = dua(jam) + ':' + dua(menit) + ':' + dua(detik);
        sisa--;
    }

    perbarui();
    setInterval(perbarui, 1000);

    // Tampilkan nama file yang dipilih
    document.getElementById('bukti_bayar').addEventListener('change', function () {
        document.getElementById('nama-file').textContent = this.files[0] ? this.files[0].name : '';
    });
</script>
@endsection