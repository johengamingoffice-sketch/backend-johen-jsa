<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectricitySetting extends Model
{
    protected $fillable = ['kapasitas_kwh', 'updated_by'];

    protected function casts(): array
    {
        return [
            'kapasitas_kwh' => 'decimal:2',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
