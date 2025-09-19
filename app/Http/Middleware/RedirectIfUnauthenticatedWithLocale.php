<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;


class RedirectIfUnauthenticatedWithLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

        // Determine the lang from the route or fallback
        $locale = $request->route('lang') ?? app()->getLocale();

        // Throw the Laravel native AuthenticationException with custom redirect
        throw new AuthenticationException(
            'Unauthenticated.',
            [],
            route('login', ['lang' => $locale])
        );
    }
}
