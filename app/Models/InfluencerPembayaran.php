<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfluencerPembayaran extends Model
{
    protected $fillable = [
        'influencer_id',
        'bulan_ke',
        'tanggal_jatuh_tempo',
        'jumlah',
        'status',
        'tanggal_bayar',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_bayar' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function influencer(): BelongsTo
    {
        return $this->belongsTo(Influencer::class);
    }
}
