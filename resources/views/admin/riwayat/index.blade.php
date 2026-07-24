@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi - Culture Bike')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#7B1E1E]">Riwayat Transaksi</h1>
            <p class="text-sm text-[#8A7B6B]">Semua transaksi sewa & servis dari seluruh pengguna.</p>
        </div>
    </div>

    {{-- Search & filter --}}
    <form method="GET" class="flex gap-3 mb-6">
        <select name="jenis" onchange="this.form.submit()"
                class="border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm bg-white">
            <option value="semua" {{ $jenis === 'semua' ? 'selected' : '' }}>Semua Jenis</option>
            <option value="sewa" {{ $jenis === 'sewa' ? 'selected' : '' }}>Sewa</option>
            <option value="servis" {{ $jenis === 'servis' ? 'selected' : '' }}>Servis</option>
        </select>

        <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nama pelanggan..."
               class="flex-1 border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">

        <button type="submit" class="bg-[#7B1E1E] text-white text-sm px-5 py-2 rounded-lg">Cari</button>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl p-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#8A7B6B] border-b border-[#F0E6C8]">
                    <th class="pb-3 font-medium">Kode</th>
                    <th class="pb-3 font-medium">Pelanggan</th>
                    <th class="pb-3 font-medium">Jenis</th>
                    <th class="pb-3 font-medium">Tanggal</th>
                    <th class="pb-3 font-medium">Total</th>
                    <th class="pb-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $item)
                    <tr class="border-b border-[#F5EFDD]">
                        <td class="py-3 font-medium text-[#2B1E1E]">{{ $item['kode'] }}</td>
                        <td class="py-3 text-[#5C4A3A]">{{ $item['user'] }}</td>
                        <td class="py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $item['jenis'] === 'sewa' ? 'bg-[#F3E2DE] text-[#7B1E1E]' : 'bg-[#DCE8F3] text-[#1E4E6B]' }}">
                                {{ ucfirst($item['jenis']) }}
                            </span>
                        </td>
                        <td class="py-3 text-[#5C4A3A]">{{ $item['tanggal']->translatedFormat('j M Y') }}</td>
                        <td class="py-3 text-[#2B1E1E] font-medium">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                        <td class="py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-[#DCF3E5] text-[#1E6B45]">
                                {{ $item['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#B9A88F]">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">{{ $riwayat->links() }}</div>
    </div>

@endsection