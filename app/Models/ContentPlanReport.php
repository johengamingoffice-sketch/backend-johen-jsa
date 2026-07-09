<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentPlanReport extends Model
{
    protected $fillable = [
        'content_plan_id',
        'week',
        'views',
        'likes',
        'comment',
        'save',
        'share',
        'followers',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'likes' => 'integer',
            'comment' => 'integer',
            'save' => 'integer',
            'share' => 'integer',
            'followers' => 'integer',
        ];
    }

    public function contentPlan(): BelongsTo
    {
        return $this->belongsTo(ContentPlan::class);
    }
}
