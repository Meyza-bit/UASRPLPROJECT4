<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sepeda', function (Blueprint $table) {
            // Jumlah unit yang tersedia sekarang. Mockup Pesan: "2 unit tersedia".
            $table->unsignedInteger('stok')->default(1)->after('kategori');

            // Jenis sepeda: Mountain Bike, Gravel Bike, Folding Bike, City Bike, dst.
            $table->string('tipe', 50)->nullable()->after('nama');

            // Kolom status dihapus. Ketersediaan sekarang dilihat dari stok:
            // stok > 0 berarti Tersedia, stok 0 berarti Habis.
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('sepeda', function (Blueprint $table) {
            $table->dropColumn(['stok', 'tipe']);
            $table->enum('status', ['tersedia', 'disewa', 'servis'])->default('tersedia');
        });
    }
};