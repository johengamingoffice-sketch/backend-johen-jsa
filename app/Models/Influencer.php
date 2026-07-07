<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    protected $fillable = [
        'no_kontrak',
        'nama',
        'mulai_kontrak',
        'habis_kontrak',
        'link_sosmed',
    ];

    protected function casts(): array
    {
        return [
            'mulai_kontrak' => 'date',
            'habis_kontrak' => 'date',
        ];
    }
}
