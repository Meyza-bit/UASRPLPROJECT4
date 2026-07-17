@extends('layouts.app')

@section('title', 'Profil - Culture Bike')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Pesan sukses --}}
    @if (session('sukses'))
        <div class="bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">
            {{ session('sukses') }}
        </div>
    @endif

    {{-- ================= KARTU PROFIL ================= --}}
    <section class="bg-[#FCF2DA] rounded-3xl p-8 md:p-10">

        {{-- Avatar + nama + email --}}
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-full bg-[#E5A82E] flex items-center justify-center shrink-0">
                <span class="text-3xl font-bold text-[#7B1E1E]">
                    {{ strtoupper(substr($user->nama_tampil, 0, 1)) }}
                </span>
            </div>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-[#2B1E1E] truncate">{{ $user->nama_tampil }}</h1>
                <p class="text-sm text-[#A89478] truncate">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="mt-8 grid grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl p-5">
                <p class="text-[10px] tracking-widest text-[#8A7B6B]">BOOKING</p>
                <p class="mt-2 text-3xl font-bold text-[#7B1E1E]">{{ $jumlahBooking }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5">
                <p class="text-[10px] tracking-widest text-[#8A7B6B]">TOTAL PENGELUARAN</p>
                <p class="mt-2 text-3xl font-bold text-[#7B1E1E]">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Info akun --}}
        <div class="mt-8">
            <h2 class="text-[11px] font-bold tracking-widest text-[#5C4A3A]">INFO AKUN</h2>

            <div class="mt-4 space-y-3.5 text-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-[#A89478] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span class="text-[#5C4A3A] w-28">Bergabung</span>
                    <span class="text-[#2B1E1E]">{{ $user->created_at->translatedFormat('M Y') }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-[#A89478] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                    </svg>
                    <span class="text-[#5C4A3A] w-28">No. HP</span>
                    <span class="text-[#2B1E1E]">{{ $user->no_hp ?: '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Tombol buka form edit --}}
        <button type="button" id="tombol-edit"
                class="mt-8 w-full bg-[#4A4034] hover:bg-[#5C5044] text-white text-sm font-medium
                       rounded-xl py-3.5 transition">
            Edit Profil Lengkap
        </button>

    </section>

    {{-- ================= FORM EDIT (tersembunyi dulu) ================= --}}
    <section id="panel-edit" class="hidden space-y-6">

        {{-- Data diri --}}
        <div class="bg-white border border-[#EFE6D2] rounded-3xl p-8">
            <h2 class="text-lg font-bold text-[#7B1E1E]">Data Diri</h2>

            <form method="POST" action="{{ route('profil.update') }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        NAMA LENGKAP
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Belum diisi"
                           class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:border-[#7B1E1E]">
                    @error('name')
                        <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                            EMAIL
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                      focus:outline-none focus:border-[#7B1E1E]">
                        @error('email')
                            <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                            NO. HP
                        </label>
                        <input type="tel" name="no_hp" id="no_hp"
                               value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                      focus:outline-none focus:border-[#7B1E1E]">
                        @error('no_hp')
                            <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#E5A82E] hover:bg-[#D69A22] text-[#5C3A0A] font-semibold
                               rounded-full py-3.5 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Ganti password --}}
        <div class="bg-white border border-[#EFE6D2] rounded-3xl p-8">
            <h2 class="text-lg font-bold text-[#7B1E1E]">Ganti Password</h2>

            <form method="POST" action="{{ route('profil.password') }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="password_lama" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                        PASSWORD LAMA
                    </label>
                    <input type="password" name="password_lama" id="password_lama"
                           class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:border-[#7B1E1E]">
                    @error('password_lama')
                        <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="password_baru" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                            PASSWORD BARU
                        </label>
                        <input type="password" name="password" id="password_baru"
                               placeholder="Minimal 8 karakter"
                               class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                      placeholder-[#B9A88F] focus:outline-none focus:border-[#7B1E1E]">
                        @error('password')
                            <p class="mt-1 text-xs text-[#8A1F1F]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-semibold tracking-wide text-[#5C4A3A] mb-2">
                            KONFIRMASI PASSWORD BARU
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full border border-[#EADCB8] rounded-xl px-4 py-3 text-sm
                                      focus:outline-none focus:border-[#7B1E1E]">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#4A4034] hover:bg-[#5C5044] text-white font-medium
                               rounded-full py-3.5 transition">
                    Simpan Password Baru
                </button>
            </form>
        </div>

    </section>

</div>

<script>
    // Tombol "Edit Profil Lengkap" -> buka/tutup form
    const panel = document.getElementById('panel-edit');

    document.getElementById('tombol-edit').addEventListener('click', function () {
        panel.classList.toggle('hidden');
        if (! panel.classList.contains('hidden')) {
            panel.scrollIntoView({ behavior: 'smooth' });
        }
    });

    // Kalau ada input yang salah, form langsung dibuka supaya pesannya kelihatan
    @if ($errors->any())
        panel.classList.remove('hidden');
    @endif
</script>
@endsection