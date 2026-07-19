<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananServis extends Model
{
    use HasFactory;

    protected $table = 'pesanan_servis';

    protected $fillable = [
        'user_id',
        'tanggal_jadwal',
        'waktu_jadwal',
        'catatan',
        'biaya_admin',
        'total_pembayaran',
        'status',
    ];

    // Relasi: pesanan servis dimiliki oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 pesanan servis punya banyak item layanan (keranjang)
    public function detail()
    {
        return $this->hasMany(PesananServisDetail::class);
    }

    // Relasi: 1 pesanan servis punya 1 data pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id')
            ->where('jenis_pesanan', 'servis');
    }
}