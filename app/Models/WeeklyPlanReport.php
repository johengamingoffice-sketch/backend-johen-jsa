<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyPlanReport extends Model
{
    protected $fillable = [
        'employee_id',
        'tanggal',
        'kategori',
        'plan_activity',
        'objective',
        'keterangan',
        'action_plan',
        'feedback_atasan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
