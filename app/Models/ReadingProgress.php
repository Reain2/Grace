<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingProgress extends Model
{
    protected $fillable = ['user_reading_plan_id', 'reading_plan_item_id', 'checked_on'];

    protected function casts(): array
    {
        return ['checked_on' => 'date'];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(UserReadingPlan::class, 'user_reading_plan_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ReadingPlanItem::class, 'reading_plan_item_id');
    }
}
