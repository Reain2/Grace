<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderDelivery extends Model
{
    protected $fillable = ['reminder_id', 'delivery_date', 'delivery_time', 'channel', 'status'];

    protected function casts(): array
    {
        return ['delivery_date' => 'date'];
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }
}
