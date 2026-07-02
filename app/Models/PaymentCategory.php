<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentCategory extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
