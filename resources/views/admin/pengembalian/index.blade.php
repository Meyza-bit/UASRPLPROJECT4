@extends('admin.layouts.app')

@section('title', 'Pengembalian Sepeda - Culture Bike')

@section('content')

    <h1 class="text-2xl font-bold text-[#7B1E1E] mb-1">Manajemen Pengembalian Unit</h1>
    <p class="text-sm text-[#8A7B6B] mb-6">Otorisasi kembalinya unit dan pengecekan kelayakan.</p>

    @if (session('sukses'))
        <div class="mb-5 bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">{{ session('sukses') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- ================= LIST ANTREAN ================= --}}
        <section class="lg:col-span-2 bg-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-[#7B1E1E]">Antrean Pengembalian</h2>
                <span class="text-xs bg-[#FBEFC2] text-[#8A6D1F] px-2.5 py-1 rounded-full">
                    {{ $antrean->count() }} Unit
                </span>
            </div>

            <div class="space-y-3">
                @forelse ($antrean as $item)
                    @php
                        $dataTombol = [
                            'id'   => $item->id,
                            'kode' => $item->kode,
                            'nama' => $item->user->nama_tampil ?? '-',
                            'unit' => $item->detail->pluck('sepeda.nama')->implode(', '),
                        ];
                    @endphp
                    <button type="button" onclick='pilihPesanan(@json($dataTombol))'
                        class="w-full text-left border border-[#F0E6C8] hover:border-[#E5A82E] rounded-xl p-4 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-[#8A7B6B]">{{ $item->kode }}</p>
                                <p class="font-semibold text-[#2B1E1E]">{{ $item->user->nama_tampil ?? '-' }}</p>
                                <p class="text-xs text-[#5C4A3A]">{{ $item->detail->pluck('sepeda.nama')->implode(', ') }}</p>
                            </div>
                            <span class="text-xs bg-[#DCF3E5] text-[#1E6B45] px-3 py-1 rounded-full">Proses</span>
                        </div>
                    </button>
                @empty
                    <p class="text-center text-[#B9A88F] py-6">Tidak ada unit yang sedang disewa.</p>
                @endforelse
            </div>
        </section>

        {{-- ================= FORMULIR INSPEKSI ================= --}}
        <aside class="bg-[#7B1E1E] rounded-2xl p-6 text-white">
            <h2 class="font-bold mb-1">✓ Formulir Inspeksi</h2>

            <div id="belumPilih" class="bg-white/10 rounded-lg p-4 text-sm text-center mt-4">
                Pilih pesanan dari daftar di kiri
            </div>

            <form id="formInspeksi" method="POST" class="hidden mt-4 space-y-4">
                @csrf

                <div id="infoPesanan" class="bg-white/10 rounded-lg p-3 text-sm"></div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-white/70 mb-2">Pengecekan Fisik</p>

                    <label class="flex items-start gap-2 mb-2">
                        <input type="checkbox" name="cek_body_cat" value="1" class="mt-1">
                        <span class="text-sm">Body &amp; Cat — pastikan tidak ada goresan atau penyok baru.</span>
                    </label>

                    <label class="flex items-start gap-2 mb-2">
                        <input type="checkbox" name="cek_rem" value="1" class="mt-1">
                        <span class="text-sm">Fungsi Rem Depan/Belakang — respon rem harus pakem.</span>
                    </label>

                    <label class="flex items-start gap-2 mb-2">
                        <input type="checkbox" name="cek_ban" value="1" class="mt-1">
                        <span class="text-sm">Tekanan Ban &amp; Velg — cek rotasi velg dan tekanan angin.</span>
                    </label>

                    <label class="flex items-start gap-2 mb-2">
                        <input type="checkbox" name="cek_kelengkapan" value="1" class="mt-1">
                        <span class="text-sm">Kelengkapan — helm, kunci pengaman, lampu harus utuh.</span>
                    </label>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-white/70 mb-2">Catatan Tambahan</p>
                    <textarea name="catatan" rows="3" placeholder="Kondisi khusus, saran perawatan..."
                              class="w-full rounded-lg px-3 py-2 text-sm text-[#2B1E1E]"></textarea>
                </div>

                <p class="text-xs text-white/70">
                    Denda keterlambatan (kalau ada) dihitung otomatis: Rp5.000/jam.
                </p>

                <button type="submit"
                        class="w-full bg-[#E5A82E] text-[#5C3A0A] font-semibold rounded-lg py-3 text-sm">
                    ✓ Konfirmasi &amp; Update Status
                </button>
            </form>
        </aside>

    </div>

    <script>
        function pilihPesanan(data) {
            document.getElementById('belumPilih').classList.add('hidden');

            const form = document.getElementById('formInspeksi');
            form.classList.remove('hidden');
            form.action = "{{ url('admin/pengembalian') }}/" + data.id;

            document.getElementById('infoPesanan').innerHTML =
                '<strong>' + data.kode + '</strong><br>' +
                data.nama + '<br>' +
                '<span class="text-white/70">' + data.unit + '</span>';
        }
    </script>

@endsection