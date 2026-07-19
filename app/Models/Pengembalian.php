<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'penyewaan_id',
        'cek_body_cat',
        'cek_rem',
        'cek_ban',
        'cek_kelengkapan',
        'catatan',
        'jam_telat',
        'denda',
        'diproses_oleh',
    ];

    protected $casts = [
        'cek_body_cat'    => 'boolean',
        'cek_rem'         => 'boolean',
        'cek_ban'         => 'boolean',
        'cek_kelengkapan' => 'boolean',
        'denda'           => 'decimal:2',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}