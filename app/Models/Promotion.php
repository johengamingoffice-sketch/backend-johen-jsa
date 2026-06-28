<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'employee_id',
        'nomor_surat',
        'posisi_lama',
        'posisi_baru',
        'divisi_lama',
        'divisi_baru',
        'atasan_lama',
        'atasan_baru',
        'tanggal_efektif',
        'jenis',
        'alasan',
        'pdf_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_efektif' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
