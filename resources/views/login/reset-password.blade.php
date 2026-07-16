@extends('layouts.login')

@section('title', 'Buat Password Baru - Culture Bike')

@section('content')
<div class="w-full max-w-lg">
    <div class="bg-[#FCF2DA] rounded-3xl p-8 md:p-12">

        {{-- Logo & judul --}}
        <div class="text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#7B1E1E] flex items-center justify-center">
                <span class="text-[#E5A82E] text-[10px] font-bold leading-tight text-center">CULTURE<br>BIKE</span>
            </div>
            <h1 class="mt-4 text-2xl font-medium text-[#7B1E1E]">Buat Password Baru</h1>
            <p class="mt-2 text-sm text-[#5C4A3A]">
                Masukkan password barumu di bawah ini.
            </p>
        </div>

        {{-- Pesan kalau token tidak valid --}}
        @if ($errors->has('email'))
            <div class="mt-6 bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
            @csrf

            {{-- Token & email disembunyikan, ikut terkirim otomatis --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            {{-- Password baru --}}
            <div>
                <label for="password" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                    PASSWORD BARU
                </label>
                <div class="relative">
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Minimal 8 karakter"
                           autofocus
                           class="w-full bg-white border border-[#EADCB8] rounded-xl px-4 py-3 pr-11 text-sm
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

            {{-- Konfirmasi password --}}
            <div>
                <label for="password_confirmation" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                    KONFIRMASI PASSWORD BARU
                </label>
                <div class="relative">
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           placeholder="Ulangi password baru"
                           class="w-full bg-white border border-[#EADCB8] rounded-xl px-4 py-3 pr-11 text-sm
                                  placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                    <button type="button"
                            data-lihat-password="password_confirmation"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#B9A88F] hover:text-[#7B1E1E]"
                            aria-label="Tampilkan konfirmasi password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-[#E5A82E] hover:bg-[#D69A22] text-[#5C3A0A] font-semibold
                           rounded-full py-3.5 transition">
                Simpan Password Baru
            </button>
        </form>

    </div>
</div>
@endsection