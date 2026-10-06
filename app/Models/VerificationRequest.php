<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationRequest extends Model
{
    protected $fillable = [
        'user_id',
        'tradition_id',
        'proof_type',
        'proof_path',
        'status',
        'is_active',
        'attempts',
        'claimed_by',
        'claimed_at',
        'decided_by',
        'decided_at',
        'reject_reason',
        'proof_delete_at',
        'proof_deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => VerificationStatus::class,
            'claimed_at' => 'datetime',
            'decided_at' => 'datetime',
            'proof_delete_at' => 'datetime',
            'proof_deleted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tradition(): BelongsTo
    {
        return $this->belongsTo(Tradition::class);
    }
}
