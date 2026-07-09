<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $fillable = [
        'nama',
        'parent_id',
        'division_id',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_position')
            ->withPivot('is_main')
            ->withTimestamps();
    }

    public function notesGiven(): HasMany
    {
        return $this->hasMany(PositionNote::class, 'from_position_id');
    }

    public function notesReceived(): HasMany
    {
        return $this->hasMany(PositionNote::class, 'to_position_id');
    }
}
