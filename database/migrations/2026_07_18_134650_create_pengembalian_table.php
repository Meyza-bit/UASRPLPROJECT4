<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->onDelete('cascade');
            $table->boolean('cek_body_cat')->default(false);
            $table->boolean('cek_rem')->default(false);
            $table->boolean('cek_ban')->default(false);
            $table->boolean('cek_kelengkapan')->default(false);
            $table->text('catatan')->nullable();
            $table->integer('jam_telat')->default(0);
            $table->decimal('denda', 10, 2)->default(0);
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};