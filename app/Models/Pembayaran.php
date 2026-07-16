<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    // Kalau nggak ditulis, Laravel nyari tabel "pembayarans"
    protected $table = 'pembayaran';

    protected $fillable = [
        'jenis_pesanan',
        'pesanan_id',
        'metode_bayar',
        'jumlah',
        'bukti_bayar',
        'status',
        'batas_waktu',
        'diverifikasi_at',
    ];

    protected $casts = [
        'jumlah'          => 'decimal:2',
        'batas_waktu'     => 'datetime',
        'diverifikasi_at' => 'datetime',
    ];

    // --- Relasi ---

    /**
     * Pesanan sewa yang dibayar.
     * Cuma dipakai kalau jenis_pesanan = 'sewa'.
     */
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class, 'pesanan_id');
    }

    // --- Scope ---

    public function scopeSewa($query)
    {
        return $query->where('jenis_pesanan', 'sewa');
    }

    public function scopeServis($query)
    {
        return $query->where('jenis_pesanan', 'servis');
    }

    // --- Helper ---

    /**
     * Batas waktu bayar sudah lewat atau belum.
     */
    public function getKadaluarsaAttribute(): bool
    {
        return now()->greaterThan($this->batas_waktu);
    }

    /**
     * Sisa waktu dalam detik. Dipakai untuk hitung mundur di halaman pembayaran.
     */
    public function getSisaDetikAttribute(): int
    {
        return max(0, now()->diffInSeconds($this->batas_waktu, false));
    }

    /**
     * Tulisan status untuk ditampilkan ke customer.
     */
    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => $this->bukti_bayar ? 'Menunggu Verifikasi' : 'Menunggu Pembayaran',
            'diverifikasi' => 'Pembayaran Terverifikasi',
            'ditolak'      => 'Pembayaran Ditolak',
            default        => $this->status,
        };
    }
}