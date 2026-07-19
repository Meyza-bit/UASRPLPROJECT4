@extends('layouts.app')

@section('title', 'Pesan Servis Sepeda - Culture Bike')

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
                Pilih layanan servis, atur jadwal, lalu konfirmasi pesanan.
            </p>
        </div>

        {{-- Toggle Sewa / Servis --}}
        <div class="flex bg-white rounded-full p-1 border border-[#E5A82E] shrink-0">
            <a href="{{ route('pesan.index') }}" class="text-[#A89478] text-sm px-6 py-2.5 rounded-full hover:text-[#7B1E1E]">
                Sewa Sepeda
            </a>
            <span class="bg-[#E5A82E] text-[#5C3A0A] text-sm font-semibold px-6 py-2.5 rounded-full">
                Servis Sepeda
            </span>
        </div>
    </section>

    {{-- Pesan sukses / error --}}
    @if (session('success'))
        <div class="bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('servis.store') }}" method="POST" id="formServis">
        @csrf

        {{-- ================= ISI ================= --}}
        <div class="grid lg:grid-cols-3 gap-6 items-start">

            {{-- ---------- KIRI: daftar layanan ---------- --}}
            <section class="lg:col-span-2 bg-[#FCF2DA] rounded-3xl p-6 md:p-8">
                <h2 class="text-xl font-bold text-[#7B1E1E]">Daftar Layanan Servis</h2>

                @php
                    $daftarLayanan = [
                        ['nama' => 'Tune-Up Lengkap', 'deskripsi' => 'Pemeriksaan menyeluruh, setel rem & gir untuk performa optimal.', 'harga' => 50000],
                        ['nama' => 'Ganti Komponen Baru', 'deskripsi' => 'Penggantian suku cadang berkualitas tinggi sesuai kebutuhan.', 'harga' => 15000],
                        ['nama' => 'Ganti Rantai', 'deskripsi' => 'Pemasangan rantai baru untuk kelancaran gowes Anda.', 'harga' => 35000],
                        ['nama' => 'Tambal / Ganti Ban', 'deskripsi' => 'Solusi cepat untuk ban bocor atau aus di perjalanan.', 'harga' => 10000],
                    ];
                @endphp

                <div class="mt-6 grid sm:grid-cols-2 gap-5">
                    @foreach ($daftarLayanan as $i => $layanan)
                        <div class="bg-white rounded-2xl p-5">
                            <h3 class="font-semibold text-[#2B1E1E]">{{ $layanan['nama'] }}</h3>
                            <p class="mt-1 text-sm text-[#8A7B6B]">{{ $layanan['deskripsi'] }}</p>

                            <div class="mt-4 flex items-center justify-between gap-3">
                                <p class="text-2xl font-bold text-[#7B1E1E]">
                                    Rp {{ number_format($layanan['harga'], 0, ',', '.') }}
                                </p>
                            </div>

                            <label class="hidden">
                                <input
                                    type="checkbox"
                                    name="layanan_checkbox[]"
                                    value="{{ $i }}"
                                    class="checkbox-layanan"
                                    data-nama="{{ $layanan['nama'] }}"
                                    data-harga="{{ $layanan['harga'] }}"
                                >
                            </label>

                            <button type="button"
                                    class="btn-tambah mt-4 w-full bg-[#4A4034] hover:bg-[#5C5044] text-white text-sm font-medium rounded-xl py-3 transition"
                                    data-index="{{ $i }}">
                                🛒 Tambah ke Keranjang
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ---------- KANAN: keranjang + jadwal ---------- --}}
            <aside class="bg-white rounded-3xl border-2 border-[#E5A82E] p-6">
                <h2 class="flex items-center gap-2 text-lg font-bold text-[#7B1E1E]">
                    🛍️ Keranjang Servis
                </h2>

                {{-- Isi keranjang --}}
                <div id="keranjangList" class="mt-5 space-y-3 min-h-16">
                    <p class="text-sm text-[#B9A88F] text-center py-4">Belum ada layanan dipilih</p>
                </div>

                {{-- Total --}}
                <div class="mt-2 pt-4 border-t border-[#E3D4B0] flex items-center justify-between">
                    <span class="text-xs tracking-widest text-[#8A7B6B]">TOTAL ESTIMASI</span>
                    <span class="text-2xl font-bold text-[#7B1E1E]">
                        Rp <span id="totalEstimasi">0</span>
                    </span>
                </div>

                {{-- Jadwal --}}
                <div class="mt-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="tanggal_jadwal" class="block text-xs text-[#5C4A3A] mb-1.5">Jadwal Servis</label>
                            <input type="date" name="tanggal_jadwal" id="tanggal_jadwal" required
                                   min="{{ now()->toDateString() }}"
                                   class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#7B1E1E]">
                        </div>

                        <div>
                            <label for="waktu_jadwal" class="block text-xs text-[#5C4A3A] mb-1.5">Waktu</label>
                            <input type="time" name="waktu_jadwal" id="waktu_jadwal"
                                   class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#7B1E1E]">
                        </div>
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs text-[#5C4A3A] mb-1.5">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan" rows="3" placeholder="Kondisi khusus, saran perawatan..."
                                  class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#7B1E1E]"></textarea>
                    </div>
                </div>

                {{-- Hidden inputs buat kirim data keranjang ke server --}}
                <div id="hiddenLayananInputs"></div>

                <button type="submit"
                        id="btnSubmitServis"
                        disabled
                        class="mt-5 w-full flex items-center justify-center gap-2 rounded-full py-3.5 font-semibold transition
                               bg-[#EDE4D2] text-[#B9A88F] cursor-not-allowed">
                    Konfirmasi & Servis
                </button>
            </aside>

        </div>
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.checkbox-layanan');
    const tombolTambah = document.querySelectorAll('.btn-tambah');
    const keranjangList = document.getElementById('keranjangList');
    const totalEstimasi = document.getElementById('totalEstimasi');
    const hiddenInputs = document.getElementById('hiddenLayananInputs');
    const btnSubmit = document.getElementById('btnSubmitServis');

    tombolTambah.forEach(btn => {
        btn.addEventListener('click', function () {
            const index = this.dataset.index;
            const checkbox = document.querySelector(`.checkbox-layanan[value="${index}"]`);
            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                this.textContent = '✓ Ditambahkan';
                this.classList.remove('bg-[#4A4034]', 'hover:bg-[#5C5044]');
                this.classList.add('bg-[#E5A82E]', 'text-[#5C3A0A]');
            } else {
                this.textContent = '🛒 Tambah ke Keranjang';
                this.classList.add('bg-[#4A4034]', 'hover:bg-[#5C5044]');
                this.classList.remove('bg-[#E5A82E]', 'text-[#5C3A0A]');
            }

            updateKeranjang();
        });
    });

    function updateKeranjang() {
        const dipilih = [...checkboxes].filter(cb => cb.checked);

        if (dipilih.length === 0) {
            keranjangList.innerHTML = '<p class="text-sm text-[#B9A88F] text-center py-4">Belum ada layanan dipilih</p>';
        } else {
            keranjangList.innerHTML = dipilih.map(cb => `
                <div class="flex items-center justify-between text-sm">
                    <span class="text-[#2B1E1E]">${cb.dataset.nama}</span>
                    <span class="text-[#7B1E1E] font-medium">Rp ${Number(cb.dataset.harga).toLocaleString('id-ID')}</span>
                </div>
            `).join('');
        }

        const total = dipilih.reduce((sum, cb) => sum + Number(cb.dataset.harga), 0);
        totalEstimasi.textContent = total.toLocaleString('id-ID');

        hiddenInputs.innerHTML = dipilih.map((cb, index) => `
            <input type="hidden" name="layanan[${index}][jenis_layanan]" value="${cb.dataset.nama}">
            <input type="hidden" name="layanan[${index}][harga_layanan]" value="${cb.dataset.harga}">
        `).join('');

        // Tombol submit nyala cuma kalau minimal 1 layanan dipilih
        if (dipilih.length > 0) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('bg-[#EDE4D2]', 'text-[#B9A88F]', 'cursor-not-allowed');
            btnSubmit.classList.add('bg-[#2F5D3F]', 'hover:bg-[#264C33]', 'text-white');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.add('bg-[#EDE4D2]', 'text-[#B9A88F]', 'cursor-not-allowed');
            btnSubmit.classList.remove('bg-[#2F5D3F]', 'hover:bg-[#264C33]', 'text-white');
        }
    }
</script>
@endsection