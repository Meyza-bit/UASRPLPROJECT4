<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            // Satu tabel ini dipakai untuk pembayaran Sewa maupun Servis,
            // sesuai class diagram: jenisPesanan ENUM('Sewa','Servis') + idPesanan.
            // Karena bisa menunjuk ke dua tabel berbeda, kolomnya tidak dikunci
            // dengan foreign key, cukup diberi index supaya pencarian tetap cepat.
            $table->enum('jenis_pesanan', ['sewa', 'servis']);
            $table->unsignedBigInteger('pesanan_id');
            $table->index(['jenis_pesanan', 'pesanan_id']);

            $table->string('metode_bayar', 50)->default('QRIS / Transfer Bank');
            $table->decimal('jumlah', 12, 2);

            // Nama file bukti transfer yang diunggah customer
            $table->string('bukti_bayar', 255)->nullable();

            $table->enum('status', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');

            // Batas waktu bayar (mockup: "Selesaikan pembayaran dalam 23:59:59")
            $table->dateTime('batas_waktu');

            // Kapan admin memverifikasi
            $table->dateTime('diverifikasi_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};