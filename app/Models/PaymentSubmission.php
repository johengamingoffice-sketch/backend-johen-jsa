<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentSubmission extends Model
{
    protected $fillable = [
        'jenis', 'detail', 'nominal', 'status',
        'tgl_bayar', 'bukti', 'pengaju_id', 'approved_by',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_bayar' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
