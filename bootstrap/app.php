<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\SetLang;
use App\Http\Middleware\CheckRoleModuleAccess;
use App\Http\Middleware\RedirectIfUnauthenticatedWithLocale;
use App\Http\Middleware\CheckUserHasActivePlan;
use App\Http\Middleware\PreventDoubleBooking;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'setlang' => SetLang::class,
            'check.access' => CheckRoleModuleAccess::class,
            'guest.lang' => RedirectIfUnauthenticatedWithLocale::class,
            'check.plan' => CheckUserHasActivePlan::class,
            'restrict.multi.booking' => PreventDoubleBooking::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
