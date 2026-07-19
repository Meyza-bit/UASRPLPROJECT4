@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Culture Bike')

@section('content')

    {{-- ================= KARTU STATISTIK ================= --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl p-5">
            <p class="text-xs text-[#8A7B6B]">TOTAL UNIT</p>
            <p class="mt-2 text-2xl font-bold text-[#E5A82E]">{{ $totalUnit }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5">
            <p class="text-xs text-[#8A7B6B]">SEWA AKTIF</p>
            <p class="mt-2 text-2xl font-bold text-[#E5A82E]">{{ $sewaAktif }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5">
            <p class="text-xs text-[#8A7B6B]">SERVIS MASUK</p>
            <p class="mt-2 text-2xl font-bold text-[#E5A82E]">{{ $servisMasuk }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border-l-4 border-[#E5A82E]">
            <p class="text-xs text-[#8A7B6B]">TOTAL PENDAPATAN</p>
            <p class="mt-2 text-2xl font-bold text-[#7B1E1E]">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </p>
        </div>

    </div>

    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- ================= TABEL PESANAN TERBARU ================= --}}
        <section class="lg:col-span-2 bg-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-[#7B1E1E]">Pesanan Terbaru</h2>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-[#8A7B6B] border-b border-[#F0E6C8]">
                        <th class="pb-2 font-medium">Nama Pelanggan</th>
                        <th class="pb-2 font-medium">Jenis</th>
                        <th class="pb-2 font-medium">Tanggal</th>
                        <th class="pb-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesananTerbaru as $p)
                        <tr class="border-b border-[#F5EFDD]">
                            <td class="py-3 text-[#2B1E1E]">{{ $p['nama'] }}</td>
                            <td class="py-3 text-[#5C4A3A]">{{ $p['jenis'] }}</td>
                            <td class="py-3 text-[#5C4A3A]">{{ $p['tanggal']->translatedFormat('j M Y') }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ in_array($p['status'], ['selesai', 'aktif'])
                                        ? 'bg-[#DCF3E5] text-[#1E6B45]'
                                        : 'bg-[#FBEFC2] text-[#8A6D1F]' }}">
                                    {{ ucfirst($p['status']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-[#B9A88F]">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        {{-- ================= PANEL STATUS UNIT ================= --}}
        <aside class="bg-white rounded-2xl p-6">
            <h2 class="font-bold text-[#7B1E1E] mb-4">Status Unit</h2>

            <div class="flex justify-between text-sm mb-1">
                <span class="text-[#5C4A3A]">Tersedia</span>
                <span class="text-[#2B1E1E] font-medium">{{ $unitTersedia }} Unit</span>
            </div>
            <div class="w-full h-2 rounded-full bg-[#F0E6C8] mb-4">
                <div class="h-2 rounded-full bg-[#E5A82E]" style="width: 100%"></div>
            </div>

            <div class="flex justify-between text-sm mb-1">
                <span class="text-[#5C4A3A]">Disewa</span>
                <span class="text-[#2B1E1E] font-medium">{{ $unitDisewa }} Unit</span>
            </div>
            <div class="w-full h-2 rounded-full bg-[#F0E6C8]">
                @php
                    $persenDisewa = $unitTersedia > 0 ? min(100, ($unitDisewa / $unitTersedia) * 100) : 0;
                @endphp
                <div class="h-2 rounded-full bg-[#7B1E1E]" style="width: {{ $persenDisewa }}%"></div>
            </div>
        </aside>

    </div>

@endsection