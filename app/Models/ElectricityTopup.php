<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectricityTopup extends Model
{
    protected $fillable = [
        'tanggal_bayar', 'periode', 'jumlah_kwh',
        'nominal', 'created_by', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'jumlah_kwh' => 'decimal:2',
            'nominal' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
