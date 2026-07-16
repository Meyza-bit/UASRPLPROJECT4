<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu baris = satu pesanan sewa
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 25)->unique();              // CB-20260518-001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_sewa');
            $table->time('jam_mulai');
            $table->unsignedTinyInteger('durasi_jam');          // 1, 3, atau 6
            $table->decimal('total', 12, 2);
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'aktif',
                'selesai',
                'batal',
            ])->default('menunggu_pembayaran');
            $table->timestamps();
        });

        // Isi keranjang: satu pesanan bisa punya beberapa sepeda
        Schema::create('penyewaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();
            $table->foreignId('sepeda_id')->constrained('sepeda')->restrictOnDelete();
            $table->unsignedInteger('qty');

            // Harga disimpan ulang di sini, bukan diambil dari tabel sepeda.
            // Supaya kalau admin ubah harga nanti, nota lama nggak ikut berubah.
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewaan_detail');
        Schema::dropIfExists('penyewaan');
    }
};