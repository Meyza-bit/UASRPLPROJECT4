@extends('layouts.login')

@section('title', 'Daftar Akun - Culture Bike')

@section('content')
<div class="w-full max-w-2xl">
    <div class="bg-[#FCF2DA] rounded-3xl p-8 md:p-12">

        {{-- Logo & judul --}}
        <div class="text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-[#FDFBF5] border-[3px] border-[#7B1E1E] flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Culture Bike" class="w-full h-full object-cover">
            </div>
            <h1 class="mt-4 text-3xl font-medium text-[#7B1E1E]">Culture Bike</h1>
            <p class="mt-2 text-sm text-[#5C4A3A] max-w-sm mx-auto">
                Buat akun untuk mulai menyewa &amp; servis unit pilihan Anda.
            </p>
        </div>

        {{-- Tab Masuk / Daftar Akun --}}
        <div class="mt-8 grid grid-cols-2 text-sm">
            <a href="{{ route('login') }}"
               class="text-center pb-3 border-b border-[#E3D4B0] text-[#5C4A3A] hover:text-[#7B1E1E]">
                Masuk
            </a>
            <span class="text-center pb-3 border-b-2 border-[#7B1E1E] text-[#7B1E1E] font-semibold">
                Daftar Akun
            </span>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
            @csrf

            <div class="grid md:grid-cols-2 gap-5">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        EMAIL
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email"
                           autofocus
                           class="w-full bg-white border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                  placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                    @error('email')
                        <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        NO. HP
                    </label>
                    <input type="tel"
                           name="no_hp"
                           id="no_hp"
                           value="{{ old('no_hp') }}"
                           placeholder="08123456789"
                           class="w-full bg-white border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                  placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                    @error('no_hp')
                        <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        PASSWORD
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Password kuat"
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

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        KONFIRMASI PASSWORD
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               placeholder="Ulangi password"
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

            </div>

            {{-- Checkbox syarat & ketentuan --}}
            <div>
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="setuju"
                           value="1"
                           {{ old('setuju') ? 'checked' : '' }}
                           class="mt-0.5 w-4 h-4 rounded border-[#D6C6A0] text-[#7B1E1E] focus:ring-[#7B1E1E]">
                    <span class="text-sm text-[#3A2A2A] leading-relaxed">
                        Saya menyetujui
                        <a href="#" class="text-[#7B1E1E] underline">syarat dan ketentuan</a>
                        serta kebijakan privasi Culture Bike.
                    </span>
                </label>
                @error('setuju')
                    <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol daftar --}}
            <button type="submit"
                    class="w-full bg-[#E5A82E] hover:bg-[#D69A22] text-[#5C3A0A] text-lg font-semibold
                           rounded-full py-4 transition flex items-center justify-center gap-2">
                Buat Akun Sekarang
                <span aria-hidden="true">&rarr;</span>
            </button>
        </form>

        {{-- Link ke login --}}
        <p class="mt-6 text-center text-sm text-[#5C4A3A]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#7B1E1E] font-medium hover:underline">
                Login di sini
            </a>
        </p>

    </div>
</div>
@endsection