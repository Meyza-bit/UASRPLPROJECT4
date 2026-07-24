<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Culture Admin')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDF6E3] font-['Poppins']">

    <div class="flex min-h-screen">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-64 bg-white border-r border-[#F0E6C8] flex flex-col shrink-0">
            <div class="px-6 py-6 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Culture Bike" class="w-9 h-9 rounded-full object-cover">
                <span class="text-lg font-bold text-[#7B1E1E]">Culture Admin</span>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                @php
                    $menu = [
                        'Dashboard' => [
                            'route' => 'admin.dashboard',
                            'icon'  => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
                        ],
                        'Manajemen Unit' => [
                            'route' => 'admin.unit.index',
                            'icon'  => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 0h-12',
                        ],
                        'Pesanan Masuk' => [
                            'route' => 'admin.pesanan-masuk.index',
                            'icon'  => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z',
                        ],
                        'Pengembalian Sepeda' => [
                            'route' => 'admin.pengembalian.index',
                            'icon'  => 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3',
                        ],
                        'Daftar Pengguna' => [
                            'route' => 'admin.pengguna.index',
                            'icon'  => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                        ],
                        'Riwayat' => [
                            'route' => 'admin.riwayat.index',
                            'icon'  => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        'Laporan' => [
                            'route' => 'admin.laporan.index',
                            'icon'  => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                        ],
                    ];
                @endphp

                @foreach ($menu as $label => $item)
                    @php $aktif = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition
                              {{ $aktif ? 'bg-[#E5A82E] text-[#5C3A0A]' : 'text-[#5C4A3A] hover:bg-[#FCF2DA]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="p-4 border-t border-[#F0E6C8]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 text-sm text-[#8A7B6B] hover:text-[#7B1E1E] px-4 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ================= KONTEN ================= --}}
        <main class="flex-1 px-8 py-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm text-[#8A7B6B]">Halo, {{ auth()->user()->nama_tampil ?? 'Admin' }}</p>
                    <p class="text-xs text-[#B9A88F]">{{ now()->translatedFormat('l, j F Y') }}</p>
                </div>
            </div>

            @if (session('sukses'))
                <div class="mb-5 bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">{{ session('sukses') }}</div>
            @endif

            @yield('content')
        </main>

    </div>

</body>
</html>