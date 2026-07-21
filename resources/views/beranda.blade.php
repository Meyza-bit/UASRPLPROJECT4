@extends('layouts.app')

@section('title', 'Culture Bike - Sewa & Servis Sepeda')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- ================= HERO ================= --}}
    <section class="bg-[#FCF2DA] rounded-3xl p-8 md:p-12">

        <div class="grid md:grid-cols-2 gap-10 items-center">

            {{-- Kiri: teks --}}
            <div>
                <span class="inline-flex items-center gap-2 bg-white rounded-full px-4 py-2 text-xs font-medium text-[#3A2A2A]">
                    <svg class="w-3.5 h-3.5 text-[#7B1E1E]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd"/>
                    </svg>
                    Tersedia di Pontianak
                </span>

                <h1 class="mt-6 text-4xl md:text-5xl font-bold leading-tight">
                    <span class="text-[#2B1E1E]">Sewa &amp; Servis Sepeda</span><br>
                    <span class="text-[#7B1E1E]">Kapan Saja, Di Mana Saja</span>
                </h1>

                <p class="mt-5 text-[#5C4A3A] leading-relaxed max-w-md">
                    Pesan sepeda online, bayar digital, dan pantau status pesanan secara real-time.
                </p>

                <a href="{{ route('katalog.index') }}"
                   class="inline-block mt-7 bg-[#1C1917] hover:bg-[#332E2B] text-white text-sm font-semibold
                          px-8 py-4 rounded-full transition">
                    Lihat Katalog
                </a>
            </div>

            {{-- Kanan: gambar --}}
            <div class="bg-white rounded-2xl aspect-square flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/bikelogo.png') }}" alt="Culture Bike Hero" class="w-full h-full object-cover">
            </div>

        </div> <!-- Tag penutup kontainer grid atas[cite: 6] -->

        {{-- Statistik --}}
        <div class="mt-12 pt-8 border-t border-[#EADCB8] grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @php
                $statistik = [
                    ['nilai' => $totalUnit, 'label' => 'UNIT SEPEDA TERSEDIA', 'emas' => true],
                    ['nilai' => '24/7',     'label' => 'LAYANAN PELANGGAN',    'emas' => false],
                    ['nilai' => '100%',     'label' => 'PEMBAYARAN DIGITAL',   'emas' => false],
                ];
            @endphp

            @foreach ($statistik as $s)
                <div class="flex flex-col items-center justify-center">
                    <p class="text-3xl md:text-4xl font-bold {{ $s['emas'] ? 'text-[#E5A82E]' : 'text-[#2B1E1E]' }}">
                        {{ $s['nilai'] }}
                    </p>
                    <p class="mt-1 text-[10px] tracking-widest text-[#8A7B6B] font-medium max-w-[150px]"> 
                        {{ $s['label'] }}
                    </p>
                </div>
            @endforeach
        </div>

    </section>

        {{-- Butuh servis --}}
        <div class="md:col-span-2 bg-[#7B1E1E] rounded-3xl p-7 relative overflow-hidden">
            <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white opacity-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
            </svg>

            <div class="relative">
                <h2 class="text-xl font-bold text-white">Butuh Servis?</h2>
                <p class="mt-3 text-sm text-[#F0D9D9] leading-relaxed">
                    Montir ahli kami akan memperbaiki sepeda Anda.
                </p>

                <a href="#" class="inline-flex items-center gap-2 mt-6 text-white text-sm font-medium
                                   border-b-2 border-white/60 pb-1 hover:border-white transition">
                    Booking Sekarang
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

    </section>

</div>
@endsection