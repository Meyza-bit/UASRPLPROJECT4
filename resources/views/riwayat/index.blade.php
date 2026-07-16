@extends('layouts.app')

@section('title', 'Riwayat - Culture Bike')

@section('content')
<div class="max-w-4xl mx-auto">

    <section class="bg-[#FCF2DA] rounded-3xl p-6 md:p-8">

        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-[#7B1E1E]">Riwayat</h1>
            <a href="{{ route('katalog.index') }}" class="text-sm text-[#7B1E1E] hover:underline">
                Sewa Lagi &rarr;
            </a>
        </div>

        {{-- Daftar pesanan --}}
        <div class="mt-5 space-y-4">
            @forelse ($riwayat as $pesanan)
                <div class="bg-white rounded-2xl p-5">

                    @foreach ($pesanan->detail as $item)
                        <div class="flex gap-4 {{ ! $loop->first ? 'mt-4 pt-4 border-t border-[#F2EBDC]' : '' }}">

                            {{-- Foto --}}
                            <div class="w-20 h-16 rounded-lg bg-[#EDE4D2] shrink-0 flex items-center justify-center overflow-hidden">
                                @if ($item->sepeda->foto)
                                    <img src="{{ asset('storage/' . $item->sepeda->foto) }}" alt="{{ $item->sepeda->nama }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-[#B9A88F] text-[9px] text-center px-1">Belum ada foto</span>
                                @endif
                            </div>

                            {{-- Nama + tanggal + durasi --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-[#2B1E1E] font-medium truncate">
                                    {{ $item->sepeda->nama }}
                                    <span class="text-[#A89478] font-normal">({{ ucfirst($item->sepeda->kategori) }})</span>
                                    @if ($item->qty > 1)
                                        <span class="text-[#A89478] font-normal">× {{ $item->qty }}</span>
                                    @endif
                                </p>

                                <div class="mt-1 flex flex-wrap gap-3 text-xs text-[#8A7B6B]">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                        </svg>
                                        {{ $pesanan->tanggal_sewa->translatedFormat('j M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $pesanan->durasi_jam }} Jam
                                    </span>
                                </div>

                                <p class="mt-2 text-[#E5A82E] font-bold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Status + tombol: cuma di baris pertama --}}
                            @if ($loop->first)
                                <div class="flex flex-col items-end justify-between shrink-0">
                                    @php
                                        $warna = match ($pesanan->status) {
                                            'selesai' => 'bg-[#DCF3E5] text-[#1E6B45]',
                                            'batal'   => 'bg-[#EDE4D2] text-[#8A7B6B]',
                                            default   => 'bg-[#FBEFC2] text-[#8A6D1F]',
                                        };
                                    @endphp

                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $warna }}">
                                        {{ $pesanan->label_status }}
                                    </span>

                                    <a href="{{ route('riwayat.show', $pesanan) }}"
                                       class="mt-4 text-sm text-[#7B1E1E] hover:underline">
                                        Rincian
                                    </a>
                                </div>
                            @endif

                        </div>
                    @endforeach

                    {{-- Kalau belum bayar, kasih jalan pintas --}}
                    @if ($pesanan->status === 'menunggu_pembayaran')
                        <a href="{{ route('pembayaran.show', $pesanan) }}"
                           class="mt-4 block text-center bg-[#7B1E1E] hover:bg-[#621818] text-white text-sm
                                  font-medium rounded-xl py-2.5 transition">
                            Lanjutkan Pembayaran
                        </a>
                    @endif

                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center">
                    <p class="text-[#7B1E1E] font-medium">Belum ada riwayat penyewaan.</p>
                    <a href="{{ route('katalog.index') }}" class="inline-block mt-3 text-sm text-[#A8791E] underline">
                        Lihat katalog sepeda
                    </a>
                </div>
            @endforelse
        </div>

        @if ($riwayat->hasPages())
            <div class="mt-6">{{ $riwayat->links() }}</div>
        @endif

        {{-- Butuh bantuan --}}
        <div class="mt-6 bg-[#7B1E1E] rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div>
                <p class="text-white font-semibold">Butuh Bantuan?</p>
                <p class="mt-1 text-sm text-[#F0D9D9]">Tim kami siap membantu servis sepeda Anda.</p>
            </div>

            <div class="text-sm md:text-right">
                <p class="text-[#F0D9D9] text-xs">Kontak Yang Bisa Dihubungi</p>
                <p class="text-white mt-1">+62 838-6991-5909</p>
                <p class="text-white">ig: culturebike.co</p>
            </div>
        </div>

    </section>

</div>
@endsection