<?php

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case ReligionAdmin = 'religion_admin';
    case Superadmin = 'superadmin';
}
