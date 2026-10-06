<?php

use App\Http\Middleware\EnsureActive;
use App\Http\Middleware\EnsureFaithVerified;
use App\Http\Middleware\EnsureRole;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'active' => EnsureActive::class,
            'faith.verified' => EnsureFaithVerified::class,
            'role' => EnsureRole::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('proofs:purge')->daily();
        $schedule->command('reminders:send')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
