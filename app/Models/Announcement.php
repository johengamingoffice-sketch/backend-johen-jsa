<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'content', 'summary', 'event_date', 'event_time', 'is_published'])]
class Announcement extends Model
{
}
