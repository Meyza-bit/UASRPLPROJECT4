@extends('admin.layouts.app')

@section('title', 'Pesanan Masuk - Culture Bike')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#7B1E1E]">Pesanan Masuk</h1>
            <p class="text-sm text-[#8A7B6B]">Verifikasi pembayaran dan kelola status pesanan sewa & servis.</p>
        </div>

        {{-- Tab Sewa / Servis --}}
        <div class="flex bg-white rounded-full p-1 border border-[#E5A82E]">
            <a href="{{ route('admin.pesanan-masuk.index', ['tab' => 'sewa']) }}"
               class="text-sm px-6 py-2.5 rounded-full font-medium
                      {{ $tab === 'sewa' ? 'bg-[#E5A82E] text-[#5C3A0A]' : 'text-[#A89478]' }}">
                Sewa Sepeda
            </a>
            <a href="{{ route('admin.pesanan-masuk.index', ['tab' => 'servis']) }}"
               class="text-sm px-6 py-2.5 rounded-full font-medium
                      {{ $tab === 'servis' ? 'bg-[#E5A82E] text-[#5C3A0A]' : 'text-[#A89478]' }}">
                Servis Sepeda
            </a>
        </div>
    </div>

    @if (session('sukses'))
        <div class="mb-5 bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">{{ session('sukses') }}</div>
    @endif

    {{-- ================= ANTREAN VERIFIKASI ================= --}}
    <section class="bg-white rounded-2xl p-6 mb-6">
        <h2 class="font-bold text-[#7B1E1E] mb-4">
            Antrean Verifikasi
            <span class="ml-2 text-xs bg-[#FBEFC2] text-[#8A6D1F] px-2.5 py-1 rounded-full">{{ $antrean->count() }} Menunggu</span>
        </h2>

        <div class="space-y-4">
            @forelse ($antrean as $item)
                <div class="border border-[#F0E6C8] rounded-xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold text-[#2B1E1E]">{{ $item->user->nama_tampil ?? '-' }}</p>

                        @if ($tab === 'servis')
                            <p class="text-xs text-[#5C4A3A]">
                                {{ $item->detail->pluck('jenis_layanan')->implode(', ') }}
                            </p>
                            <p class="text-xs text-[#8A7B6B]">
                                Total: Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-xs text-[#5C4A3A]">
                                {{ $item->detail->pluck('sepeda.nama')->implode(', ') }}
                            </p>
                            <p class="text-xs text-[#8A7B6B]">
                                Kode: {{ $item->kode }} — Total: Rp {{ number_format($item->total, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($item->pembayaran && $item->pembayaran->bukti_bayar)
                            <a href="{{ asset('storage/' . $item->pembayaran->bukti_bayar) }}" target="_blank"
                               class="text-xs text-[#7B1E1E] underline">Lihat Bukti</a>
                        @endif

                        <form method="POST"
                              action="{{ $tab === 'servis'
                                    ? route('admin.pesanan-masuk.servis.approve', $item)
                                    : route('admin.pesanan-masuk.sewa.approve', $item) }}">
                            @csrf
                            <button type="submit" class="bg-[#2F5D3F] text-white text-xs font-medium px-4 py-2 rounded-lg">
                                ✓ Approve
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ $tab === 'servis'
                                    ? route('admin.pesanan-masuk.servis.reject', $item)
                                    : route('admin.pesanan-masuk.sewa.reject', $item) }}"
                              onsubmit="return confirm('Tolak pembayaran ini?')">
                            @csrf
                            <button type="submit" class="bg-[#8A1F1F] text-white text-xs font-medium px-4 py-2 rounded-lg">
                                ✕ Reject
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-[#B9A88F] py-6">Tidak ada pesanan yang menunggu verifikasi.</p>
            @endforelse
        </div>
    </section>

    {{-- ================= SEDANG BERJALAN ================= --}}
    <section class="bg-white rounded-2xl p-6">
        <h2 class="font-bold text-[#7B1E1E] mb-4">Sedang Berjalan</h2>

        <div class="space-y-4">
            @forelse ($berjalan as $item)
                <div class="border border-[#F0E6C8] rounded-xl p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold text-[#2B1E1E]">{{ $item->user->nama_tampil ?? '-' }}</p>
                        <p class="text-xs text-[#8A7B6B]">
                            {{ $tab === 'servis' ? 'Pesanan servis sedang dikerjakan' : ('Kode: ' . $item->kode) }}
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ $tab === 'servis'
                                ? route('admin.pesanan-masuk.servis.selesai', $item)
                                : route('admin.pesanan-masuk.sewa.selesai', $item) }}"
                          onsubmit="return confirm('Tandai pesanan ini selesai?')">
                        @csrf
                        <button type="submit" class="bg-[#E5A82E] text-[#5C3A0A] text-xs font-semibold px-4 py-2 rounded-lg">
                            Tandai Selesai
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-center text-[#B9A88F] py-6">Tidak ada pesanan yang sedang berjalan.</p>
            @endforelse
        </div>
    </section>

@endsection