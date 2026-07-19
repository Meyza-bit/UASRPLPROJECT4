@extends('layouts.app')

@section('title', 'Katalog Servis - Culture Bike')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- ===== Tab Katalog Sepeda / Katalog Servis ===== --}}
    <div class="flex gap-8 border-b border-[#E3D4B0] mb-6 px-2">
        <a href="{{ route('katalog.index') }}"
           class="pb-3 text-sm text-[#5C4A3A] hover:text-[#7B1E1E]">
            Katalog Sepeda
        </a>
        <span class="pb-3 text-sm font-semibold text-[#7B1E1E] border-b-2 border-[#7B1E1E]">
            Katalog Servis
        </span>
    </div>

    <section class="bg-[#FCF2DA] rounded-3xl p-6 md:p-10">
        <h1 class="text-4xl md:text-5xl font-bold text-[#7B1E1E]">Layanan Servis</h1>

        <div class="mt-8 grid sm:grid-cols-2 gap-5 max-w-4xl">
            @foreach ($layanan as $kode => $item)
                <div class="bg-[#FDF8ED] border border-[#EFE0BE] rounded-2xl p-6 flex flex-col">
                    <h3 class="text-xl font-bold text-[#2B1E1E]">{{ $item['nama'] }}</h3>
                    <p class="mt-2 text-sm text-[#5C4A3A] leading-relaxed flex-1">{{ $item['deskripsi'] }}</p>

                    <p class="mt-4 text-2xl font-bold text-[#7B1E1E]">
                        @if ($item['mulai'])
                            <span class="text-base font-normal text-[#A89478]">mulai </span>
                        @endif
                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>

                    <a href="{{ route('servis.create') }}"
                       class="mt-4 w-full flex items-center justify-center gap-2 bg-[#E5A82E] hover:bg-[#D69A22]
                              text-[#5C3A0A] text-sm font-semibold rounded-xl py-3 transition">
                        Pesan Sekarang
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection