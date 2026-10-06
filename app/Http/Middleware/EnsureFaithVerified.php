<?php

namespace App\Http\Middleware;

use App\Enums\VerificationStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFaithVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->verification_status === VerificationStatus::Approved, 403);

        return $next($request);
    }
}
