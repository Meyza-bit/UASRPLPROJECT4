@extends('layouts.app')

@section('title', 'Katalog Sepeda - Culture Bike')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Tab Katalog Sepeda / Katalog Servis --}}
    <div class="flex gap-8 border-b border-[#E3D4B0] mb-6 px-2">
        <span class="pb-3 text-sm font-semibold text-[#7B1E1E] border-b-2 border-[#7B1E1E]">
            Katalog Sepeda
        </span>
        <a href="{{ route('katalog.servis.index') }}"
           class="pb-3 text-sm text-[#5C4A3A] hover:text-[#7B1E1E]">
            Katalog Servis
        </a>
    </div>

    <div class="bg-[#FCF2DA] rounded-3xl p-6 md:p-10">
        {{-- isi katalog sepeda --}}

        {{-- ===== Judul + filter ===== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-[#7B1E1E]">Katalog Sepeda</h1>

            <div class="flex flex-wrap gap-3">
                @foreach (['semua' => 'Semua', 'premium' => 'Premium', 'standar' => 'Standar'] as $key => $label)
                    <a href="{{ route('katalog.index', $key === 'semua' ? [] : ['kategori' => $key]) }}"
                       class="px-7 py-2.5 rounded-full text-sm font-medium transition
                              {{ $kategori === $key
                                    ? 'bg-[#E5A82E] text-[#5C3A0A]'
                                    : 'border border-[#E3D4B0] text-[#7B1E1E] hover:bg-[#F7E8C4]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ===== Daftar sepeda ===== --}}
        @forelse ($sepeda as $item)
            <div class="bg-[#FDF8ED] rounded-2xl p-4 mb-4 flex flex-col sm:flex-row sm:items-center gap-5">

                {{-- Foto --}}
                <div class="w-full sm:w-32 h-28 rounded-xl overflow-hidden bg-[#EDE4D2] shrink-0">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}"
                             alt="{{ $item->nama }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#B9A88F] text-xs">
                            Belum ada foto
                        </div>
                    @endif
                </div>

                {{-- Nama + kategori --}}
                <div class="sm:w-56 shrink-0">
                    <p class="text-[#7B1E1E] font-medium">{{ $item->nama }} ({{ $item->kode }})</p>
                    <span class="inline-block mt-2 px-2.5 py-1 rounded text-[10px] font-semibold tracking-wide
                                 {{ $item->kategori === 'premium'
                                        ? 'bg-[#F3E2DE] text-[#7B1E1E]'
                                        : 'bg-[#DCDCDC] text-[#4A4A4A]' }}">
                        {{ strtoupper($item->kategori) }}
                    </span>
                </div>

                {{-- Harga --}}
                <div class="flex-1 grid grid-cols-3 gap-3">
                    @foreach ([
                        'PER JAM' => $item->harga_per_jam,
                        '3 JAM'   => $item->harga_3jam,
                        '6 JAM'   => $item->harga_6jam,
                    ] as $label => $harga)
                        <div>
                            <p class="text-[9px] tracking-widest text-[#A89478]">{{ $label }}</p>
                            <p class="text-[#7B1E1E] font-medium mt-0.5">
                                Rp {{ number_format($harga, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Status --}}
                <div class="shrink-0">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-medium
                                 {{ $item->tersedia
                                        ? 'bg-[#DCF3E5] text-[#1E6B45]'
                                        : 'bg-[#FBEFC2] text-[#8A6D1F]' }}">
                        {{ $item->badge }}
                    </span>
                </div>

            </div>
        @empty
            <div class="bg-[#FDF8ED] rounded-2xl p-12 text-center">
                <p class="text-[#7B1E1E] font-medium">Belum ada sepeda di kategori ini.</p>
                <a href="{{ route('katalog.index') }}" class="inline-block mt-3 text-sm text-[#A8791E] underline">
                    Lihat semua sepeda
                </a>
            </div>
        @endforelse

        {{-- ===== Pagination ===== --}}
        @if ($sepeda->hasPages())
            <div class="mt-8">
                {{ $sepeda->links() }}
            </div>
        @endif

    </div>
</div>
@endsection