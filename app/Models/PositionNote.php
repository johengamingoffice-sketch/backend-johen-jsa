<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionNote extends Model
{
    protected $fillable = [
        'from_position_id',
        'to_position_id',
        'situasi',
        'catatan',
        'komitmen',
        'rekomendasi_jenjang',
        'bulan',
        'tahun',
        'created_by',
    ];

    public function fromPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'from_position_id');
    }

    public function toPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'to_position_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PositionNoteComment::class);
    }
}
