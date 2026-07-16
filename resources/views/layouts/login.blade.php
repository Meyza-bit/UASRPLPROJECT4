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
<body class="bg-[#FDFBF5] text-[#3A2A2A] min-h-screen flex flex-col">

    <main class="flex-1 flex items-center justify-center px-4 py-12">
        @yield('content')
    </main>

    <footer class="border-t border-[#EFE6D2] px-6 py-6">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-lg font-bold text-[#7B1E1E]">Culture Bike</p>
            <p class="text-xs text-[#8A7B6B]">&copy; {{ date('Y') }} Culture Bike. Crafted for the urban cyclist.</p>
            <div class="flex gap-5 text-xs text-[#5C4A3A]">
                <a href="#" class="hover:text-[#7B1E1E]">Tentang Kami</a>
                <a href="#" class="hover:text-[#7B1E1E]">Layanan</a>
                <a href="#" class="hover:text-[#7B1E1E]">Bantuan</a>
            </div>
        </div>
    </footer>

    {{-- Tombol mata: tampilkan / sembunyikan password --}}
    <script>
        document.querySelectorAll('[data-lihat-password]').forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                const input = document.getElementById(tombol.dataset.lihatPassword);
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>

</body>
</html>