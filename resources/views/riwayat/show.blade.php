@extends('layouts.app')

@section('title', 'Status Pesanan - Culture Bike')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-[#FCF2DA] rounded-2xl overflow-hidden">

        {{-- Garis merah di atas nota --}}
        <div class="h-2 bg-[#7B1E1E]"></div>

        <div class="p-8 md:p-10">

            {{-- Judul + nomor transaksi --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-[#2B1E1E]">Status Pesanan</h1>

                    @php
                        $warna = match ($penyewaan->status) {
                            'selesai' => 'bg-[#DCF3E5] text-[#1E6B45]',
                            'batal'   => 'bg-[#EDE4D2] text-[#8A7B6B]',
                            default   => 'bg-[#DCF3E5] text-[#1E6B45]',
                        };
                        $teks = match ($penyewaan->status) {
                            'selesai' => 'Pesanan Selesai',
                            'batal'   => 'Pesanan Dibatalkan',
                            'aktif'   => 'Pesanan Berlangsung',
                            default   => $penyewaan->label_status,
                        };
                    @endphp

                    <span class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full text-xs font-medium {{ $warna }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        {{ $teks }}
                    </span>
                </div>

                <div class="sm:text-right shrink-0">
                    <p class="text-[10px] tracking-widest text-[#8A7B6B]">NO. TRANSAKSI</p>
                    <p class="text-[#7B1E1E] font-bold">{{ $penyewaan->kode }}</p>
                    <p class="mt-1 text-xs text-[#8A7B6B]">
                        {{ $penyewaan->created_at->translatedFormat('l, j F Y') }}
                    </p>
                </div>
            </div>

            {{-- Informasi pelanggan --}}
            <div class="mt-8">
                <p class="text-[10px] font-bold tracking-widest text-[#8A7B6B]">INFORMASI PELANGGAN</p>

                <div class="mt-3 grid sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <p class="text-xs text-[#8A7B6B]">Nama Lengkap</p>
                        <p class="mt-0.5 text-[#2B1E1E] font-semibold">{{ $penyewaan->user->nama_tampil }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#8A7B6B]">Metode Pembayaran</p>
                        <p class="mt-0.5 text-[#2B1E1E] font-semibold">
                            {{ $pembayaran?->metode_bayar ?? 'Belum dipilih' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail penyewaan --}}
            <div class="mt-7">
                <p class="text-[10px] font-bold tracking-widest text-[#8A7B6B]">DETAIL PENYEWAAN</p>

                <div class="mt-3 bg-[#F2EBDC] rounded-xl p-5 relative overflow-hidden">

                    {{-- Cap VERIFIED muncul kalau pembayaran sudah diverifikasi --}}
                    @if ($pembayaran?->status === 'diverifikasi')
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-5xl font-black text-[#7B1E1E]
                                     opacity-[0.07] tracking-widest pointer-events-none select-none">
                            VERIFIED
                        </span>
                    @endif

                    <div class="relative space-y-4">
                        @foreach ($penyewaan->detail as $item)
                            <div class="flex justify-between gap-4 {{ ! $loop->first ? 'pt-4 border-t border-[#E3D9C4]' : '' }}">
                                <div class="min-w-0">
                                    <p class="text-[#2B1E1E] font-semibold truncate">
                                        {{ $item->sepeda->nama }}
                                        @if ($item->qty > 1)
                                            <span class="text-[#8A7B6B] font-normal">× {{ $item->qty }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs">
                                        <span class="text-[#7B1E1E]">ID: {{ $item->sepeda->kode }}</span>
                                        <span class="text-[#8A7B6B]"> • {{ $item->sepeda->tipe }}</span>
                                    </p>
                                </div>
                                <p class="text-[#2B1E1E] font-semibold shrink-0">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach

                        <div class="pt-4 border-t border-[#E3D9C4] grid sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-[#8A7B6B]">Durasi Sewa</p>
                                <p class="mt-0.5 text-[#2B1E1E] font-semibold">{{ $penyewaan->durasi_jam }} Jam</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#8A7B6B]">Mulai</p>
                                <p class="mt-0.5 text-[#2B1E1E] font-semibold">
                                    {{ $penyewaan->tanggal_sewa->translatedFormat('j M Y') }},
                                    {{ substr($penyewaan->jam_mulai, 0, 5) }} WIB
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Rincian biaya --}}
            <div class="mt-7 pt-6 border-t border-dashed border-[#D6C6A0] space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#5C4A3A]">Biaya Sewa</span>
                    <span class="text-[#2B1E1E]">Rp {{ number_format($penyewaan->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#5C4A3A]">Biaya Layanan</span>
                    <span class="text-[#2B1E1E]">Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#8A7B6B]">Pajak (0%)</span>
                    <span class="text-[#8A7B6B]">Rp 0</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-[#D6C6A0] flex justify-between items-center">
                <span class="text-lg font-bold text-[#2B1E1E]">Total Pembayaran</span>
                <span class="text-2xl font-bold text-[#7B1E1E]">
                    Rp {{ number_format($penyewaan->total, 0, ',', '.') }}
                </span>
            </div>

            {{-- Tombol --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="window.print()"
                        class="flex-1 flex items-center justify-center gap-2 bg-[#7B1E1E] hover:bg-[#621818]
                               text-white text-sm font-medium rounded-full py-3 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5z"/>
                    </svg>
                    Cetak Nota
                </button>

                <a href="{{ route('riwayat.index') }}"
                   class="flex-1 flex items-center justify-center gap-2 border border-[#D6C6A0] text-[#5C4A3A]
                          hover:bg-[#F7EBCE] text-sm font-medium rounded-full py-3 transition">
                    Kembali ke Riwayat
                </a>
            </div>

        </div>
    </div>

</div>
@endsection