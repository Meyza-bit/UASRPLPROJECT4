@extends('layouts.login')

@section('title', 'Masuk - Culture Bike')

@section('content')
<div class="w-full max-w-2xl">
    <div class="bg-[#FCF2DA] rounded-3xl p-8 md:p-10">

        {{-- Logo & judul --}}
        <div class="text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#7B1E1E] flex items-center justify-center">
                <span class="text-[#E5A82E] text-[10px] font-bold leading-tight text-center">CULTURE<br>BIKE</span>
            </div>
            <h1 class="mt-3 text-lg text-[#7B1E1E]">Culture Bike</h1>
            <p class="mt-1 text-sm text-[#A89478]">Masuk untuk melanjutkan pesananmu</p>
        </div>

        {{-- Tab Masuk / Daftar Akun --}}
        <div class="mt-6 grid grid-cols-2 text-sm">
            <span class="text-center pb-2 border-b-2 border-[#7B1E1E] text-[#7B1E1E] font-medium">
                Masuk
            </span>
            <a href="{{ route('register') }}"
               class="text-center pb-2 border-b border-[#E3D4B0] text-[#A89478] hover:text-[#7B1E1E]">
                Daftar Akun
            </a>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Email"
                       autofocus
                       class="w-full bg-[#F7EBCE] border border-[#EADCB8] rounded-xl px-4 py-3.5 text-sm
                              placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                @error('email')
                    <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="relative">
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Password"
                           class="w-full bg-[#F7EBCE] border border-[#EADCB8] rounded-xl px-4 py-3.5 pr-11 text-sm
                                  placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                    <button type="button"
                            data-lihat-password="password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#B9A88F] hover:text-[#7B1E1E]"
                            aria-label="Tampilkan password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                @enderror
            </div>

            {{-- Lupa password --}}
            <div class="text-right">
                <a href="#" class="text-sm text-[#5C4A3A] hover:text-[#7B1E1E]">Lupa Password?</a>
            </div>

            {{-- Tombol masuk --}}
            <button type="submit"
                    class="w-full bg-[#E5A82E] hover:bg-[#D69A22] text-[#5C3A0A] font-semibold tracking-wide
                           rounded-full py-3.5 transition">
                MASUK
            </button>
        </form>

        {{-- Link ke daftar --}}
        <p class="mt-5 text-center text-sm text-[#A89478]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#7B1E1E] font-semibold hover:underline">
                Daftar Sekarang
            </a>
        </p>

    </div>
</div>
@endsection