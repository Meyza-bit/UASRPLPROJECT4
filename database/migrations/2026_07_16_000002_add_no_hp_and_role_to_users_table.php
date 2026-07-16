<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->enum('role', ['pelanggan', 'admin'])->default('pelanggan')->after('password');
        });

        // Mockup Daftar Akun nggak minta nama, jadi name boleh kosong dulu.
        // Nanti diisi lewat halaman Profil.
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'role']);
            $table->string('name')->nullable(false)->change();
        });
    }
};