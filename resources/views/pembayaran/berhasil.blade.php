@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - Culture Bike')

@section('content')
<div class="max-w-lg mx-auto">

    <div class="bg-[#FCF2DA] rounded-3xl p-8 md:p-10 text-center">

        {{-- Ikon centang --}}
        <div class="w-20 h-20 mx-auto rounded-full bg-[#DCF3E5] flex items-center justify-center">
            <svg class="w-11 h-11 text-[#1E6B45]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="mt-5 text-3xl font-bold text-[#7B1E1E]">Pembayaran Berhasil!</h1>
        <p class="mt-3 text-sm text-[#5C4A3A]">
            Terima kasih! {{ isset($penyewaan) ? 'Pesananmu' : 'Servismu' }} sedang kami siapkan.
        </p>

        {{-- Ringkasan transaksi --}}
        <div class="mt-8 bg-white rounded-2xl p-6 text-left">
            <p class="text-[10px] font-bold tracking-widest text-[#8A7B6B]">RINGKASAN TRANSAKSI</p>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-[#8A7B6B]">No. {{ isset($penyewaan) ? 'Transaksi' : 'Pesanan' }}</span>
                    <span class="text-[#7B1E1E] font-semibold">
                        {{ isset($penyewaan) ? $penyewaan->kode : '#' . $pesanan->id }}
                    </span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#8A7B6B]">Metode</span>
                    <span class="text-[#2B1E1E]">{{ $pembayaran->metode_bayar }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#8A7B6B]">Tanggal</span>
                    <span class="text-[#2B1E1E]">
                        {{ (isset($penyewaan) ? $penyewaan->created_at : $pesanan->created_at)->translatedFormat('j F Y') }}
                    </span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#8A7B6B]">Status</span>
                    <span class="px-2.5 py-1 rounded-full bg-[#FBEFC2] text-[#8A6D1F] text-xs font-medium">
                        {{ $pembayaran->label_status }}
                    </span>
                </div>

                <div class="pt-3 border-t border-[#EFE6D2] flex justify-between gap-4">
                    <span class="text-[#8A7B6B]">Total</span>
                    <span class="text-lg font-bold text-[#7B1E1E]">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <p class="mt-6 text-xs text-[#8A7B6B] leading-relaxed">
            Kamu dapat melihat detail pesanan atau riwayat penyewaan di halaman
            <a href="{{ route('profil.index') }}" class="text-[#7B1E1E] font-medium underline">Profil</a>.
        </p>

        {{-- Tombol --}}
        <a href="{{ url('/') }}"
           class="inline-flex items-center justify-center gap-2 mt-6 w-full bg-[#7B1E1E] hover:bg-[#621818]
                  text-white font-medium rounded-full py-3.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.5a1.125 1.125 0 001.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21.375h4.125A1.125 1.125 0 0019.5 20.25V9.75"/>
            </svg>
            Kembali ke Beranda
        </a>

        {{-- Jaminan --}}
        <div class="mt-8 pt-6 border-t border-[#EADCB8] grid grid-cols-3 gap-3">
            @foreach ([
                ['label' => 'TERVERIFIKASI', 'path' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'AMAN',          'path' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z'],
                ['label' => 'READY RIDE',    'path' => 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5'],
            ] as $item)
                <div>
                    <svg class="w-5 h-5 mx-auto text-[#A89478]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['path'] }}"/>
                    </svg>
                    <p class="mt-1.5 text-[9px] tracking-wider text-[#A89478]">{{ $item['label'] }}</p>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection