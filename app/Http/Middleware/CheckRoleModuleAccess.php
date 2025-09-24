<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckRoleModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try{

                $user = $request->user();
                //$currentRoute = Route::currentRouteName();
                $isAdmin = ($user->is_super_admin == 5); // or whatever logic you use
                $isTrainer = ($user->is_trainer == 5); // or whatever logic you use
                $isClient = ($user->is_client == 5); // or whatever logic you use

                // Always set `isAdmin` flag in request attributes
                $request->attributes->set('isAdmin', $isAdmin);
                $request->attributes->set('isTrainer', $isTrainer);
                $request->attributes->set('isClient', $isClient);

            return $next($request);

        } catch (Throwable $e) {
                // Custom logging to 'check-role-module-access-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/check-role-module-access-error.log')
                ])->error("Check Role Module Access Failed: " . $e->getMessage(), [
                    'user_id' => $request->user()->id ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 
            return response(view('errors.500')); 

        } 

            
    }
}
