@extends('admin.layouts.app')

@section('title', 'Detail Pengguna - Culture Bike')

@section('content')

    <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-[#7B1E1E] mb-4 inline-block">&larr; Kembali ke daftar</a>

    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- ================= INFO USER ================= --}}
        <section class="bg-white rounded-2xl p-6">
            <div class="w-16 h-16 rounded-full bg-[#E5A82E] flex items-center justify-center text-[#5C3A0A] text-xl font-bold mb-4">
                {{ strtoupper(substr($pengguna->nama_tampil, 0, 1)) }}
            </div>

            <h2 class="font-bold text-[#2B1E1E] text-lg">{{ $pengguna->nama_tampil }}</h2>
            <p class="text-sm text-[#8A7B6B]">{{ $pengguna->email }}</p>

            <div class="mt-4 pt-4 border-t border-[#F0E6C8] space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#8A7B6B]">No. HP</span>
                    <span class="text-[#2B1E1E]">{{ $pengguna->no_hp ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#8A7B6B]">Bergabung</span>
                    <span class="text-[#2B1E1E]">{{ $pengguna->created_at->translatedFormat('j F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#8A7B6B]">Total Sewa</span>
                    <span class="text-[#2B1E1E]">{{ $riwayatSewa->count() }}x</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#8A7B6B]">Total Servis</span>
                    <span class="text-[#2B1E1E]">{{ $riwayatServis->count() }}x</span>
                </div>
            </div>
        </section>

        {{-- ================= RIWAYAT TRANSAKSI ================= --}}
        <section class="lg:col-span-2 space-y-6">

            {{-- Riwayat Sewa --}}
            <div class="bg-white rounded-2xl p-6">
                <h3 class="font-bold text-[#7B1E1E] mb-4">Riwayat Sewa</h3>

                <div class="space-y-3">
                    @forelse ($riwayatSewa as $item)
                        <div class="flex justify-between items-center border-b border-[#F5EFDD] pb-3">
                            <div>
                                <p class="text-sm font-medium text-[#2B1E1E]">{{ $item->kode }}</p>
                                <p class="text-xs text-[#8A7B6B]">{{ $item->tanggal_sewa->translatedFormat('j M Y') }} — {{ $item->durasi_jam }} Jam</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#7B1E1E]">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                <span class="text-xs text-[#8A7B6B]">{{ $item->label_status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-[#B9A88F] py-4 text-sm">Belum pernah sewa.</p>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Servis --}}
            <div class="bg-white rounded-2xl p-6">
                <h3 class="font-bold text-[#7B1E1E] mb-4">Riwayat Servis</h3>

                <div class="space-y-3">
                    @forelse ($riwayatServis as $item)
                        <div class="flex justify-between items-center border-b border-[#F5EFDD] pb-3">
                            <div>
                                <p class="text-sm font-medium text-[#2B1E1E]">Pesanan #{{ $item->id }}</p>
                                <p class="text-xs text-[#8A7B6B]">{{ \Illuminate\Support\Carbon::parse($item->tanggal_jadwal)->translatedFormat('j M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#7B1E1E]">Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</p>
                                <span class="text-xs text-[#8A7B6B]">{{ ucfirst($item->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-[#B9A88F] py-4 text-sm">Belum pernah servis.</p>
                    @endforelse
                </div>
            </div>

        </section>

    </div>

@endsection