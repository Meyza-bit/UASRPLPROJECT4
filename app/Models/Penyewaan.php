<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyewaan extends Model
{
    use HasFactory;

    // Kalau nggak ditulis, Laravel nyari tabel "penyewaans"
    protected $table = 'penyewaan';

    protected $fillable = [
        'kode',
        'user_id',
        'tanggal_sewa',
        'jam_mulai',
        'durasi_jam',
        'total',
        'status',
    ];

    protected $casts = [
        'tanggal_sewa' => 'date',
        'durasi_jam'   => 'integer',
        'total'        => 'decimal:2',
    ];

    // --- Relasi ---

    // Pesanan ini punya siapa
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Isi keranjangnya: bisa lebih dari satu sepeda
    public function detail(): HasMany
    {
        return $this->hasMany(PenyewaanDetail::class);
    }

    // --- Helper ---

    /**
     * Bikin nomor transaksi: CB-20260716-001
     * Angka belakang urut per hari, mulai dari 001 lagi tiap ganti tanggal.
     */
    public static function buatKode(): string
    {
        $tanggal = now()->format('Ymd');

        $jumlahHariIni = static::whereDate('created_at', now()->toDateString())->count();

        return 'CB-' . $tanggal . '-' . str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Tulisan status yang ditampilkan ke customer (mockup Riwayat: "Proses" / "Selesai").
     */
    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'aktif'               => 'Proses',
            'selesai'             => 'Selesai',
            'batal'               => 'Dibatalkan',
            default               => $this->status,
        };
    }

    /**
     * Total jumlah sepeda dalam pesanan ini.
     */
    public function getTotalUnitAttribute(): int
    {
        return $this->detail->sum('qty');
    }
}