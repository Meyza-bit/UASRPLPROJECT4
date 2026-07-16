<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_layanan');
            $table->date('tanggal_jadwal');
            $table->time('waktu_jadwal')->nullable();
            $table->boolean('jemput_di_rumah')->default(false);
            $table->string('alamat_jemput')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('harga_layanan', 10, 2);
            $table->decimal('biaya_admin', 10, 2)->default(0);
            $table->decimal('total_pembayaran', 10, 2);
            $table->enum('status', ['proses', 'selesai', 'ditolak'])->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_servis');
    }
};