<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_pesanan',
        'jenis_pesanan',
        'metode_bayar',
        'jumlah',
        'bukti_bayar',
        'status',
    ];

    // Relasi dinamis: karena id_pesanan bisa nunjuk ke pesanan_sewa ATAU pesanan_servis,
    // kita bikin method biasa (bukan relasi Eloquent standar) buat ambil datanya
    public function getPesananAttribute()
    {
        if ($this->jenis_pesanan === 'servis') {
            return PesananServis::find($this->id_pesanan);
        }

        // Nanti kalau model PesananSewa punya Mey udah ada, tinggal aktifkan ini:
        // return PesananSewa::find($this->id_pesanan);
        return null;
    }
}