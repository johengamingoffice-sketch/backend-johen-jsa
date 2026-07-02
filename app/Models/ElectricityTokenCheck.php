<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectricityTokenCheck extends Model
{
    protected $fillable = [
        'tanggal_check', 'sisa_kwh', 'terpakai',
        'status', 'checked_by', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_check' => 'date',
            'sisa_kwh' => 'decimal:2',
            'terpakai' => 'decimal:2',
        ];
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
