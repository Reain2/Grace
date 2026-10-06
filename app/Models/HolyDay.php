<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HolyDay extends Model
{
    protected $fillable = ['tradition_id', 'name', 'date', 'description', 'source', 'is_annual'];

    protected function casts(): array
    {
        return ['date' => 'date', 'is_annual' => 'boolean'];
    }

    public function tradition(): BelongsTo
    {
        return $this->belongsTo(Tradition::class);
    }
}
