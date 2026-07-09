<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentPlan extends Model
{
    protected $fillable = [
        'posisi',
        'judul',
        'take',
        'post',
        'days',
        'waktu',
        'akun',
        'deskripsi',
        'pic',
        'pic_utama',
        'jenis_desain',
        'topic',
        'creator',
        'goals_content',
        'content_pillar',
        'type_of_content',
        'reference_content',
        'storyline',
        'caption',
        'revisi',
        'acc_to_posting',
        'link',
        'deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'acc_to_posting' => 'boolean',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ContentPlanReport::class);
    }
}
