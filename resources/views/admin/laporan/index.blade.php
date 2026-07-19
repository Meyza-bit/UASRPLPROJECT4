@extends('admin.layouts.app')

@section('title', 'Laporan - Culture Bike')

@section('content')

    <h1 class="text-2xl font-bold text-[#7B1E1E] mb-1">Laporan</h1>
    <p class="text-sm text-[#8A7B6B] mb-6">
        {{ $laporanBulanan[0]['bulan'] ?? '' }} &ndash; {{ $laporanBulanan[count($laporanBulanan) - 1]['bulan'] ?? '' }}
    </p>

    {{-- ================= RINGKASAN OPERASIONAL ================= --}}
    <section class="bg-white rounded-2xl p-6 mb-6">
        <h2 class="font-bold text-[#7B1E1E]">Ringkasan Operasional</h2>
        <p class="text-sm text-[#8A7B6B] mb-5">Laporan bersih kinerja unit dan keuangan secara keseluruhan.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-[#FCF2DA] rounded-xl p-5 text-center">
                <p class="text-xs text-[#8A6D1F] font-medium">TOTAL PENDAPATAN</p>
                <p class="mt-2 text-xl font-bold text-[#7B1E1E]">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-[#FCF2DA] rounded-xl p-5 text-center">
                <p class="text-xs text-[#8A6D1F] font-medium">UNIT TERSEWA</p>
                <p class="mt-2 text-xl font-bold text-[#7B1E1E]">{{ $totalUnitTersewa }}</p>
            </div>

            <div class="bg-[#FCF2DA] rounded-xl p-5 text-center">
                <p class="text-xs text-[#8A6D1F] font-medium">SERVIS SELESAI</p>
                <p class="mt-2 text-xl font-bold text-[#7B1E1E]">{{ $totalServisSelesai }}</p>
            </div>
        </div>
    </section>

    {{-- ================= LAPORAN BULANAN ================= --}}
    <section class="bg-white rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-[#7B1E1E]">Laporan Bulanan</h2>
            <a href="{{ route('admin.laporan.export') }}"
               class="bg-[#FCF2DA] text-[#7B1E1E] text-xs font-semibold px-4 py-2 rounded-lg">
                &darr; Export CSV
            </a>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#8A7B6B] border-b border-[#F0E6C8]">
                    <th class="pb-3 font-medium">Bulan</th>
                    <th class="pb-3 font-medium">Pendapatan</th>
                    <th class="pb-3 font-medium">Unit Tersewa</th>
                    <th class="pb-3 font-medium">Servis Selesai</th>
                    <th class="pb-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanBulanan as $baris)
                    <tr class="border-b border-[#F5EFDD]">
                        <td class="py-3 font-medium text-[#7B1E1E]">{{ $baris['bulan'] }}</td>
                        <td class="py-3 text-[#2B1E1E]">Rp {{ number_format($baris['pendapatan'], 0, ',', '.') }}</td>
                        <td class="py-3 text-[#5C4A3A]">{{ $baris['unit_tersewa'] }} Unit</td>
                        <td class="py-3 text-[#5C4A3A]">{{ $baris['servis_selesai'] }}</td>
                        <td class="py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-[#DCF3E5] text-[#1E6B45]">
                                Selesai
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

@endsection