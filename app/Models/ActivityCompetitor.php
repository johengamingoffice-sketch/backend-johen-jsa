<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityCompetitor extends Model
{
    protected $fillable = [
        'employee_id',
        'jenis',
        'tanggal_analysis',
        'nama_competitor',
        'platform',
        'kategori_produk',
        'link',
        'strength',
        'weakness',
        'opportunity',
        'threat',
        'kesimpulan',
        'feedback_atasan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_analysis' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
