<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalAsset extends Model
{
    protected $fillable = [
        'nama_aset', 'tagihan', 'jatuh_tempo', 'nominal',
        'status', 'tgl_bayar', 'keterangan', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'jatuh_tempo' => 'date',
            'tgl_bayar' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
