<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserReadingPlan extends Model
{
    protected $fillable = ['user_id', 'reading_plan_id', 'plan_version', 'items_snapshot', 'started_on', 'status'];

    protected function casts(): array
    {
        return ['started_on' => 'date', 'items_snapshot' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ReadingPlan::class, 'reading_plan_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ReadingProgress::class);
    }
}
