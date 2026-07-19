<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan_servis', function (Blueprint $table) {
            $table->dropColumn(['jemput_di_rumah', 'alamat_jemput']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan_servis', function (Blueprint $table) {
            $table->boolean('jemput_di_rumah')->default(false);
            $table->string('alamat_jemput')->nullable();
        });
    }
};