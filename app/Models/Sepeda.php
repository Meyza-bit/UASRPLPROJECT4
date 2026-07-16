<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sepeda extends Model
{
    use HasFactory;

    // Laravel defaultnya nyari tabel "sepedas", jadi harus ditulis manual
    protected $table = 'sepeda';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'harga_per_jam',
        'harga_3jam',
        'harga_6jam',
        'foto',
        'status',
        'is_aktif',
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
        'harga_3jam'    => 'decimal:2',
        'harga_6jam'    => 'decimal:2',
        'is_aktif'      => 'boolean',
    ];

    // --- Scope: dipakai buat filter di katalog ---

    // Sepeda yang tampil di katalog customer (yang non-aktif disembunyikan)
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeKategori($query, $kategori)
    {
        if (in_array($kategori, ['premium', 'standar'])) {
            return $query->where('kategori', $kategori);
        }
        return $query; // "semua" -> nggak difilter
    }

    // --- Helper ---

    // Badge di katalog: "Tersedia" / "Habis"
    public function getBadgeAttribute(): string
    {
        return $this->status === 'tersedia' ? 'Tersedia' : 'Habis';
    }

    public function getTersediaAttribute(): bool
    {
        return $this->status === 'tersedia';
    }

    // Ambil harga sesuai durasi sewa (1 / 3 / 6 jam)
    public function hargaDurasi(int $jam): float
    {
        return match ($jam) {
            3 => (float) $this->harga_3jam,
            6 => (float) $this->harga_6jam,
            default => (float) $this->harga_per_jam * $jam,
        };
    }
}