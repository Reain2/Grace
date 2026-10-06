<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReadingPlan extends Model
{
    protected $fillable = ['tradition_id', 'title', 'description', 'total_days', 'source', 'is_active', 'version', 'created_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function tradition(): BelongsTo
    {
        return $this->belongsTo(Tradition::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReadingPlanItem::class);
    }
}
