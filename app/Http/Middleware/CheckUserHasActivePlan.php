<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Throwable;
use Auth;

class CheckUserHasActivePlan
{
    public function handle(Request $request, Closure $next)
    {
        try{
                // Determine the lang from the route or fallback
                $locale = $request->route('lang') ?? app()->getLocale();

                $user = Auth::user();

                $activePlan = $user->userPackages
                    ->filter(fn($package) => $package->isActive())
                    ->first();

                if (!$activePlan) {

                    return redirect()->route('packages.required', [
                        'lang' => $locale
                    ]);
                }

                
            return $next($request);
               
        } catch (Throwable $e) {
                // Custom logging to 'check-user-has-active-plan-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/check-user-has-active-plan-error.log')
                ])->error("Check User Has Active Plan Failed: " . $e->getMessage(), [
                    'user_id' => $request->user()->id ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 
            return response(view('errors.500')); 

        } 
    }
}
