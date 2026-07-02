<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetUsageCheck extends Model
{
    protected $fillable = [
        'ruangan', 'hari', 'tanggal',
        'penggunaan_wifi', 'penggunaan_ethernet',
        'checked_by', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'penggunaan_wifi' => 'decimal:2',
            'penggunaan_ethernet' => 'decimal:2',
        ];
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
