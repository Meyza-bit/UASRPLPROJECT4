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
            // idPesanan sengaja TANPA foreign key constraint,
            // karena bisa merujuk ke tabel pesanan_sewa ATAU pesanan_servis
            // (dibedakan lewat kolom jenis_pesanan)
            $table->unsignedBigInteger('id_pesanan');
            $table->enum('jenis_pesanan', ['sewa', 'servis']);
            $table->string('metode_bayar', 50);
            $table->decimal('jumlah', 12, 2);
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};