<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_servis', function (Blueprint $table) {
            $table->dropColumn(['jenis_layanan', 'harga_layanan']);
        });

        Schema::create('pesanan_servis_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_servis_id')->constrained('pesanan_servis')->onDelete('cascade');
            $table->string('jenis_layanan');
            $table->decimal('harga_layanan', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_servis', function (Blueprint $table) {
            $table->string('jenis_layanan')->nullable();
            $table->decimal('harga_layanan', 10, 2)->nullable();
        });

        Schema::dropIfExists('pesanan_servis_detail');
    }
};