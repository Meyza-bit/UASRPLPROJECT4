@extends('admin.layouts.app')

@section('title', 'Manajemen Unit - Culture Bike')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#7B1E1E]">Manajemen Unit Sepeda</h1>
            <p class="text-sm text-[#8A7B6B]">Kelola inventaris sepeda, status ketersediaan, dan detail harga sewa.</p>
        </div>

        <button type="button" onclick="bukaModalTambah()"
                class="bg-[#E5A82E] hover:bg-[#D19A25] text-[#5C3A0A] font-semibold text-sm px-5 py-2.5 rounded-full transition">
            + Tambah Unit
        </button>
    </div>

    @if (session('sukses'))
        <div class="mb-5 bg-[#DCF3E5] text-[#1E6B45] text-sm rounded-xl px-5 py-3">{{ session('sukses') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-5 bg-[#FBE3E3] text-[#8A1F1F] text-sm rounded-xl px-5 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search & filter --}}
    <form method="GET" class="flex gap-3 mb-6">
        <select name="kategori" onchange="this.form.submit()"
                class="border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm bg-white">
            <option value="semua" {{ request('kategori', 'semua') === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
            <option value="premium" {{ request('kategori') === 'premium' ? 'selected' : '' }}>Premium</option>
            <option value="standar" {{ request('kategori') === 'standar' ? 'selected' : '' }}>Standar</option>
        </select>

        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari unit atau kode..."
               class="flex-1 border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">

        <button type="submit" class="bg-[#7B1E1E] text-white text-sm px-5 py-2 rounded-lg">Cari</button>
    </form>

    {{-- Grid kartu unit --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($unit as $item)
            <div class="bg-white rounded-2xl overflow-hidden">
                <div class="relative h-40 bg-[#EDE4D2] flex items-center justify-center">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-[#B9A88F] text-xs">Belum ada foto</span>
                    @endif
                    <span class="absolute top-2 right-2 px-2 py-1 rounded text-[10px] font-semibold
                        {{ $item->kategori === 'premium' ? 'bg-[#F3E2DE] text-[#7B1E1E]' : 'bg-white text-[#4A4A4A]' }}">
                        {{ strtoupper($item->kategori) }}
                    </span>
                </div>

                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <h3 class="font-semibold text-[#2B1E1E] text-sm">{{ $item->nama }}</h3>
                        <span class="text-[#7B1E1E] font-bold text-sm">Rp{{ number_format($item->harga_per_jam / 1000, 0) }}k</span>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs {{ $item->is_aktif ? 'text-[#1E6B45]' : 'text-[#B9A88F]' }}">
                            ● {{ $item->is_aktif ? 'Tersedia' : 'Non-aktif' }}
                        </span>
                        <form method="POST" action="{{ route('admin.unit.toggle', $item) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-10 h-5 rounded-full relative transition {{ $item->is_aktif ? 'bg-[#E5A82E]' : 'bg-[#DCDCDC]' }}">
                                <span class="absolute top-0.5 w-4 h-4 bg-white rounded-full transition
                                    {{ $item->is_aktif ? 'right-0.5' : 'left-0.5' }}"></span>
                            </button>
                        </form>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button type="button"
                                onclick='bukaModalEdit(@json($item))'
                                class="flex-1 border border-[#E3D4B0] text-[#7B1E1E] text-xs font-medium rounded-lg py-2">
                            ✎ Edit
                        </button>
                        <form method="POST" action="{{ route('admin.unit.destroy', $item) }}"
                              onsubmit="return confirm('Hapus unit {{ $item->nama }}?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full border border-[#F3D4D4] text-[#8A1F1F] text-xs font-medium rounded-lg py-2">
                                🗑 Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-4 text-center text-[#B9A88F] py-10">Belum ada unit sepeda.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $unit->links() }}</div>

    {{-- ================= MODAL TAMBAH/EDIT ================= --}}
    <div id="modalUnit" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h2 id="modalJudul" class="font-bold text-[#7B1E1E] text-lg mb-4">Tambah Unit</h2>

            <form id="formUnit" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                {{-- Hidden input buat spoofing method PUT pas edit. Kosong = POST biasa (tambah baru) --}}
                <input type="hidden" name="_method" id="f_method" value="">

                <input type="text" name="kode" id="f_kode" placeholder="Kode (misal CB-009)" required
                       class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                <input type="text" name="nama" id="f_nama" placeholder="Nama sepeda" required
                       class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                <input type="text" name="tipe" id="f_tipe" placeholder="Tipe (misal Mountain Bike)" required
                       class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">

                <select name="kategori" id="f_kategori" required class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                    <option value="standar">Standar</option>
                    <option value="premium">Premium</option>
                </select>

                <input type="number" name="stok" id="f_stok" placeholder="Stok" required min="0"
                       class="w-full border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">

                <div class="grid grid-cols-3 gap-2">
                    <input type="number" name="harga_per_jam" id="f_harga_per_jam" placeholder="Harga/jam" required
                           class="border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                    <input type="number" name="harga_3jam" id="f_harga_3jam" placeholder="Harga 3 jam" required
                           class="border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                    <input type="number" name="harga_6jam" id="f_harga_6jam" placeholder="Harga 6 jam" required
                           class="border border-[#E3D4B0] rounded-lg px-3 py-2 text-sm">
                </div>

                <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="w-full text-sm">

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="tutupModal()"
                            class="flex-1 border border-[#E3D4B0] text-[#5C4A3A] rounded-lg py-2.5 text-sm">Batal</button>
                    <button type="submit"
                            class="flex-1 bg-[#7B1E1E] text-white rounded-lg py-2.5 text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModalTambah() {
            document.getElementById('modalJudul').textContent = 'Tambah Unit';
            document.getElementById('formUnit').action = "{{ route('admin.unit.store') }}";
            document.getElementById('f_method').value = ''; // POST biasa
            document.getElementById('formUnit').reset();
            document.getElementById('modalUnit').classList.remove('hidden');
        }

        function bukaModalEdit(item) {
            document.getElementById('modalJudul').textContent = 'Edit Unit';
            document.getElementById('formUnit').action = "{{ url('admin/unit') }}/" + item.id;
            document.getElementById('f_method').value = 'PUT'; // spoof method jadi PUT

            document.getElementById('f_kode').value = item.kode;
            document.getElementById('f_nama').value = item.nama;
            document.getElementById('f_tipe').value = item.tipe;
            document.getElementById('f_kategori').value = item.kategori;
            document.getElementById('f_stok').value = item.stok;
            document.getElementById('f_harga_per_jam').value = item.harga_per_jam;
            document.getElementById('f_harga_3jam').value = item.harga_3jam;
            document.getElementById('f_harga_6jam').value = item.harga_6jam;

            document.getElementById('modalUnit').classList.remove('hidden');
        }

        function tutupModal() {
            document.getElementById('modalUnit').classList.add('hidden');
        }
    </script>

@endsection