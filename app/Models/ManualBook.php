<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualBook extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'thumbnail',
        'file_pdf',
    ];
}
