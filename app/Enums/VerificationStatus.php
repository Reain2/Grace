<?php

namespace App\Enums;

enum VerificationStatus: string
{
    case Pending = 'pending';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case NeedsSuperadmin = 'needs_superadmin';
}
