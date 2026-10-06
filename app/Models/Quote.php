<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quote extends Model
{
    protected $fillable = ['tradition_id', 'body', 'source', 'scheduled_for', 'is_active', 'created_by'];

    protected function casts(): array
    {
        return ['scheduled_for' => 'date', 'is_active' => 'boolean'];
    }

    public function tradition(): BelongsTo
    {
        return $this->belongsTo(Tradition::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quote_bookmarks');
    }
}
