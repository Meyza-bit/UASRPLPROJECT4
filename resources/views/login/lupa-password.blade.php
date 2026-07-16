@extends('layouts.login')

@section('title', 'Lupa Password - Culture Bike')

@section('content')
<div class="w-full max-w-lg">
    <div class="bg-[#FCF2DA] rounded-3xl p-8 md:p-12">

        {{-- Logo & judul --}}
        <div class="text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#7B1E1E] flex items-center justify-center">
                <span class="text-[#E5A82E] text-[10px] font-bold leading-tight text-center">CULTURE<br>BIKE</span>
            </div>
            <h1 class="mt-4 text-2xl font-medium text-[#7B1E1E]">Lupa Password</h1>
            <p class="mt-2 text-sm text-[#5C4A3A] max-w-sm mx-auto">
                Masukkan email akunmu. Kami akan mengirim link untuk membuat password baru.
            </p>
        </div>

        {{-- Pesan setelah link dikirim --}}
        @if (session('sukses'))
            <div class="mt-6 bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">
                {{ session('sukses') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
            @csrf

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

            <button type="submit"
                    class="w-full bg-[#E5A82E] hover:bg-[#D69A22] text-[#5C3A0A] font-semibold
                           rounded-full py-3.5 transition">
                Kirim Link Reset
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#5C4A3A]">
            Ingat passwordmu?
            <a href="{{ route('login') }}" class="text-[#7B1E1E] font-medium hover:underline">
                Kembali ke Masuk
            </a>
        </p>

    </div>
</div>
@endsection