<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananServisDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_servis_detail';

    protected $fillable = [
        'pesanan_servis_id',
        'jenis_layanan',
        'harga_layanan',
    ];

    // Relasi: setiap detail/item ini milik 1 pesanan servis
    public function pesananServis()
    {
        return $this->belongsTo(PesananServis::class);
    }
}