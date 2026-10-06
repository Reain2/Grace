<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActive
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if($request->user()?->status === UserStatus::Suspended, 403, 'Akun ditangguhkan.');

        return $next($request);
    }
}
