<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Culture Bike')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#FDFBF5] text-[#3A2A2A]">

    {{-- ================= NAVBAR ================= --}}
    <nav class="bg-[#FDFBF5] px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
                <div class="w-9 h-9 rounded-full bg-[#7B1E1E] flex items-center justify-center text-[#E5A82E] text-[9px] font-bold leading-none text-center">
                    CB
                </div>
                <span class="text-xl font-bold text-[#7B1E1E]">Culture Bike</span>
            </a>

            {{-- Menu tengah --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                @php
                    $menu = [
                        'Beranda' => url('/'),
                        'Katalog' => route('katalog.index'),
                        'Pesan'   => '#',
                        'Riwayat' => '#',
                    ];
                @endphp

                @foreach ($menu as $label => $link)
                    @php $aktif = request()->url() === $link; @endphp
                    <a href="{{ $link }}"
                       class="{{ $aktif
                            ? 'text-[#7B1E1E] font-semibold border-b-2 border-[#7B1E1E] pb-0.5'
                            : 'text-[#3A2A2A] hover:text-[#7B1E1E]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Kanan: beda kalau sudah login --}}
            @auth
                <a href="#" class="flex items-center gap-2 shrink-0">
                    <span class="text-sm font-medium text-[#7B1E1E]">Profil</span>
                    <span class="w-8 h-8 rounded-full bg-[#E5A82E] flex items-center justify-center text-[#7B1E1E] text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </a>
            @else
                <a href="#" class="shrink-0 bg-[#7B1E1E] text-white text-sm font-medium px-5 py-2.5 rounded-full hover:bg-[#621818] transition">
                    Login / Daftar
                </a>
            @endauth

        </div>
    </nav>

    {{-- ================= ISI HALAMAN ================= --}}
    <main class="px-6 pb-12">
        @yield('content')
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="border-t border-[#EFE6D2] px-6 py-8">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <p class="text-lg font-bold text-[#7B1E1E]">Culture Bike</p>
                <p class="text-xs text-[#8A7B6B] mt-1">&copy; {{ date('Y') }} Culture Bike. Crafted for quality.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-5 text-xs text-[#5C4A3A]">
                <a href="#" class="hover:text-[#7B1E1E]">Tentang Kami</a>
                <a href="#" class="hover:text-[#7B1E1E]">Layanan</a>
                <a href="#" class="hover:text-[#7B1E1E]">Syarat &amp; Ketentuan</a>
                <a href="#" class="hover:text-[#7B1E1E]">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

</body>
</html>