@extends('admin.layouts.app')

@section('title', 'Daftar Pengguna - Culture Bike')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#7B1E1E]">Daftar Pengguna</h1>
            <p class="text-sm text-[#8A7B6B]">Kelola data keanggotaan pelanggan Culture Bike.</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..."
               class="flex-1 border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="bg-[#7B1E1E] text-white text-sm px-5 py-2 rounded-lg">Cari</button>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl p-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#8A7B6B] border-b border-[#F0E6C8]">
                    <th class="pb-3 font-medium">Pengguna</th>
                    <th class="pb-3 font-medium">No. HP</th>
                    <th class="pb-3 font-medium">Bergabung</th>
                    <th class="pb-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengguna as $item)
                    <tr class="border-b border-[#F5EFDD]">
                        <td class="py-3">
                            <p class="text-[#2B1E1E] font-medium">{{ $item->nama_tampil }}</p>
                            <p class="text-xs text-[#8A7B6B]">{{ $item->email }}</p>
                        </td>
                        <td class="py-3 text-[#5C4A3A]">{{ $item->no_hp ?? '-' }}</td>
                        <td class="py-3 text-[#5C4A3A]">{{ $item->created_at->translatedFormat('j M Y') }}</td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.pengguna.show', $item) }}"
                               class="text-xs text-[#7B1E1E] underline">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-[#B9A88F]">Belum ada pengguna terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">{{ $pengguna->links() }}</div>
    </div>

@endsection