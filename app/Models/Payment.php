<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_number', 'payment_category_id', 'vendor', 'amount',
        'payment_date', 'due_date', 'period', 'status',
        'proof_file', 'notes', 'asset_id', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['belum_dibayar', 'tertunda']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }
}
