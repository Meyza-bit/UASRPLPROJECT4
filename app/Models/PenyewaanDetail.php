<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyewaanDetail extends Model
{
    use HasFactory;

    // Kalau nggak ditulis, Laravel nyari tabel "penyewaan_details"
    protected $table = 'penyewaan_detail';

    protected $fillable = [
        'penyewaan_id',
        'sepeda_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty'          => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    // --- Relasi ---

    // Baris ini bagian dari pesanan yang mana
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    // Sepeda apa yang dipesan
    public function sepeda(): BelongsTo
    {
        return $this->belongsTo(Sepeda::class);
    }
}