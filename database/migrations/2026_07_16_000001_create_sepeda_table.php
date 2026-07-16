<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sepeda', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();          // CB-001
            $table->string('nama', 100);
            $table->enum('kategori', ['premium', 'standar']);
            $table->decimal('harga_per_jam', 10, 2);
            $table->decimal('harga_3jam', 10, 2);
            $table->decimal('harga_6jam', 10, 2);
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['tersedia', 'disewa', 'servis'])->default('tersedia');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sepeda');
    }
};