@extends('layouts.app')

@section('title', 'Pesan Sewa Sepeda - Culture Bike')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <section class="bg-[#FCF2DA] rounded-3xl px-8 py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
        <div>
            <h1 class="flex items-center gap-3 text-2xl md:text-3xl font-bold text-[#7B1E1E]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="5.5" cy="17.5" r="3.5"/>
                    <circle cx="18.5" cy="17.5" r="3.5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 17.5l4-9h5l4 9M9.5 8.5h6"/>
                </svg>
                Pesan Sewa &amp; Servis Sepeda
            </h1>
            <p class="mt-2 text-sm text-[#5C4A3A]">
                Tambah dan hapus item sesukamu di dalam keranjang, lalu konfirmasi pesanan.
            </p>
        </div>

        {{-- Toggle Sewa / Servis --}}
        <div class="flex bg-white rounded-full p-1 border border-[#E5A82E] shrink-0">
            <span class="bg-[#E5A82E] text-[#5C3A0A] text-sm font-semibold px-6 py-2.5 rounded-full">
                Sewa Sepeda
            </span>
            <a href="{{ route('servis.create') }}" class="text-[#A89478] text-sm px-6 py-2.5 rounded-full hover:text-[#7B1E1E]">
                Servis Sepeda
            </a>
        </div>
    </section>

    {{-- Pesan sukses / error --}}
    @if (session('sukses'))
        <div class="bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">
            {{ session('sukses') }}
        </div>
    @endif

    @error('keranjang')
        <div class="bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">{{ $message }}</div>
    @enderror

    @error('tanggal')
        <div class="bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">{{ $message }}</div>
    @enderror

    {{-- ================= ISI ================= --}}
    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- ---------- KIRI: daftar sepeda ---------- --}}
        <section class="lg:col-span-2 bg-[#FCF2DA] rounded-3xl p-6 md:p-8">
            <h2 class="text-xl font-bold text-[#7B1E1E]">Daftar Sepeda Tersedia</h2>

            <div class="mt-6 grid sm:grid-cols-2 gap-5">
                @foreach ($sepeda as $item)
                    <div class="bg-white rounded-2xl overflow-hidden {{ $item->tersedia ? '' : 'opacity-60' }}">

                        {{-- Foto --}}
                        <div class="h-40 bg-[#EDE4D2] flex items-center justify-center">
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-[#B9A88F] text-xs">Belum ada foto</span>
                            @endif
                        </div>

                        <div class="p-5">
                            {{-- Nama + kategori --}}
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-semibold text-[#2B1E1E]">{{ $item->nama }}</h3>
                                <span class="shrink-0 px-2.5 py-1 rounded text-[10px] font-semibold tracking-wide
                                             {{ $item->kategori === 'premium'
                                                    ? 'bg-[#F3E2DE] text-[#7B1E1E]'
                                                    : 'bg-[#DCDCDC] text-[#4A4A4A]' }}">
                                    {{ strtoupper($item->kategori) }}
                                </span>
                            </div>

                            {{-- Tipe + stok --}}
                            <p class="mt-1.5 text-sm">
                                <span class="text-[#7B1E1E]">{{ $item->tipe }}</span>
                                <span class="text-[#A89478]"> • {{ $item->teks_stok }}</span>
                            </p>

                            @if ($item->tersedia)
                                <form method="POST" action="{{ route('pesan.tambah') }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="sepeda_id" value="{{ $item->id }}">

                                    {{-- Harga + stepper qty --}}
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-2xl font-bold text-[#7B1E1E]">
                                            Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}<span class="text-sm font-normal text-[#A89478]">/jam</span>
                                        </p>

                                        <div class="flex items-center gap-1 bg-[#FCF2DA] rounded-full p-1">
                                            <button type="button" data-qty="kurang"
                                                    class="w-7 h-7 rounded-full bg-white text-[#7B1E1E] font-bold leading-none">−</button>
                                            <input type="number" name="qty" value="1" min="1" max="{{ $item->stok }}"
                                                   class="w-9 text-center bg-transparent border-0 text-sm font-semibold
                                                          focus:outline-none [appearance:textfield]
                                                          [&::-webkit-outer-spin-button]:appearance-none
                                                          [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" data-qty="tambah"
                                                    class="w-7 h-7 rounded-full bg-[#E5A82E] text-[#5C3A0A] font-bold leading-none">+</button>
                                        </div>
                                    </div>

                                    <button type="submit"
                                            class="mt-4 w-full bg-[#4A4034] hover:bg-[#5C5044] text-white text-sm font-medium
                                                   rounded-xl py-3 transition">
                                        Tambah ke Pesanan
                                    </button>
                                </form>
                            @else
                                <p class="mt-4 text-2xl font-bold text-[#B9A88F]">
                                    Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}<span class="text-sm font-normal">/jam</span>
                                </p>
                                <p class="mt-4 w-full bg-[#EDE4D2] text-[#8A7B6B] text-sm text-center rounded-xl py-3">
                                    Stok Habis
                                </p>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </section>

        {{-- ---------- KANAN: keranjang ---------- --}}
        <aside class="bg-white rounded-3xl border-2 border-[#E5A82E] p-6">
            <h2 class="flex items-center gap-2 text-lg font-bold text-[#7B1E1E]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12A1.125 1.125 0 0119.75 21.75H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
                Keranjang Sewa
            </h2>

            {{-- Jadwal: berubah -> halaman langsung dimuat ulang --}}
            <form method="POST" action="{{ route('pesan.jadwal') }}" id="form-jadwal" class="mt-5 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tanggal" class="block text-xs text-[#5C4A3A] mb-1.5">Jadwal Sewa</label>
                        <input type="date" name="tanggal" id="tanggal"
                               value="{{ $jadwal['tanggal'] }}"
                               min="{{ now()->toDateString() }}"
                               class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm
                                      focus:outline-none focus:border-[#7B1E1E]">
                    </div>

                    <div>
                        <label for="jam" class="block text-xs text-[#5C4A3A] mb-1.5">Waktu</label>
                        <input type="time" name="jam" id="jam"
                               value="{{ $jadwal['jam'] }}"
                               class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm
                                      focus:outline-none focus:border-[#7B1E1E]">
                    </div>
                </div>

                <div>
                    <label for="durasi" class="block text-xs text-[#5C4A3A] mb-1.5">Lama Sewa</label>
                    <select name="durasi" id="durasi"
                            class="w-36 border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm bg-white
                                   focus:outline-none focus:border-[#7B1E1E]">
                        @foreach ([1 => '1 Jam', 3 => '3 Jam', 6 => '6 Jam'] as $nilai => $label)
                            <option value="{{ $nilai }}" {{ $jadwal['durasi'] == $nilai ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Isi keranjang --}}
            <div class="mt-5 space-y-3 min-h-24">
                @forelse ($keranjang as $item)
                    <div class="flex items-start justify-between gap-3 text-sm">
                        <div class="min-w-0">
                            <p class="text-[#2B1E1E] font-medium truncate">{{ $item['sepeda']->nama }}</p>
                            <p class="text-xs text-[#A89478]">
                                Qty {{ $item['qty'] }} × Rp {{ number_format($item['harga'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[#7B1E1E] font-medium">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                            <form method="POST" action="{{ route('pesan.hapus') }}">
                                @csrf
                                <input type="hidden" name="sepeda_id" value="{{ $item['sepeda']->id }}">
                                <button type="submit" class="text-[#B9A88F] hover:text-[#8A1F1F]" aria-label="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#B9A88F] text-center py-6">
                        Keranjang masih kosong.<br>Pilih sepeda di sebelah kiri.
                    </p>
                @endforelse
            </div>

            {{-- Total --}}
            <div class="mt-5 pt-5 border-t border-[#E3D4B0] flex items-center justify-between">
                <span class="text-xs tracking-widest text-[#8A7B6B]">TOTAL ESTIMASI</span>
                <span class="text-2xl font-bold text-[#7B1E1E]">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            {{-- Buat pesanan --}}
            <form method="POST" action="{{ route('pesan.simpan') }}" class="mt-5">
                @csrf
                <button type="submit"
                        {{ empty($keranjang) ? 'disabled' : '' }}
                        class="w-full flex items-center justify-center gap-2 rounded-full py-3.5 font-semibold transition
                               {{ empty($keranjang)
                                    ? 'bg-[#EDE4D2] text-[#B9A88F] cursor-not-allowed'
                                    : 'bg-[#2F5D3F] hover:bg-[#264C33] text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buat Pesanan
                </button>
            </form>
        </aside>

    </div>
</div>

<script>
    // Tombol - dan + di kartu sepeda
    document.querySelectorAll('[data-qty]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            const input = tombol.parentElement.querySelector('input[name="qty"]');
            const maks   = parseInt(input.max);
            let nilai    = parseInt(input.value) || 1;

            nilai = tombol.dataset.qty === 'tambah' ? nilai + 1 : nilai - 1;

            input.value = Math.min(Math.max(nilai, 1), maks);
        });
    });

    // Ubah tanggal / jam / lama sewa -> langsung kirim, total ikut berubah
    ['tanggal', 'jam', 'durasi'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', function () {
            document.getElementById('form-jadwal').submit();
        });
    });
    
    // Kalau jam mulai di atas 22.30, opsi 3 & 6 jam dimatikan
    function aturDurasi() {
        const jam    = document.getElementById('jam').value;
        const durasi = document.getElementById('durasi');
        if (!jam) return;

        const [j, m] = jam.split(':').map(Number);
        const menit  = j * 60 + m;
        const lewat  = menit > (22 * 60 + 30);

        Array.from(durasi.options).forEach(function (opt) {
            if (opt.value === '3' || opt.value === '6') {
                opt.disabled = lewat;
            }
        });

        if (lewat && (durasi.value === '3' || durasi.value === '6')) {
            durasi.value = '1';
        }
    }

    aturDurasi();
    document.getElementById('jam').addEventListener('change', aturDurasi);
</script>
@endsection