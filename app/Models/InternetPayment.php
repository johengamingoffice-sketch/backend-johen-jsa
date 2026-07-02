<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetPayment extends Model
{
    protected $fillable = [
        'nama_internet', 'provider', 'pic', 'jabatan',
        'masa_tenggang', 'biaya', 'status', 'tgl_bayar',
        'keterangan', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'masa_tenggang' => 'date',
            'tgl_bayar' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
