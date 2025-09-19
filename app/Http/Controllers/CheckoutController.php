<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\CheckoutService;


class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CheckoutService $checkoutService)
    {
        $this->middleware(['guest.lang','check.access']);
        $this->checkoutService = $checkoutService;
    }


    /**
     * checkout
     */
    public function checkout(string $locale, string $type, string $id)
    {
        try {
                // pepare checkout
                $data = $this->checkoutService->prepareCheckout($type, $id);

                return view('checkout.page', [
                        'data' => $data['item'],
                        'intent' => $data['intent'],
                        'type' => $data['type']
                ]);
                
        } catch (Throwable $e) {
                // Custom logging to 'checkout-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/checkout-controller-error.log')
                ])->error("Checkout Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'type' =>$type,
                    'id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 

        }
    }


    /**
     * process payment
     */
    public function process(Request $request, string $locale, string $type, string $id)
    {
        try {
                // process payment
                $data = $this->checkoutService->handlePayment($request, $type, $id);

                return view('checkout.confirmation', [
                        'message' => $data['message']
                ]);

                
        } catch (Throwable $e) {
                // Custom logging to 'checkout-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/checkout-controller-error.log')
                ])->error("Checkout Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'type' =>$type,
                    'id' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 

        }
    }

     /**
     * Display a listing of the resource.
     */
    public function confirmation(Request $request)
    {
        try {
                // get the route name
                $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

                //set request-prev-route-name used when locking screen manually
                session(['request-prev-route-name'=> $routeName]);

                $isAdmin = $request->get('isAdmin');
                $permissions = $request->get('current_module_permissions', []);

            return view('checkout.confirmation', [
                'permissions' => $permissions,
                'isAdmin' => $isAdmin
            ]);

        } catch (Throwable $e) {
                // Custom logging to 'checkout-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/checkout-controller-error.log')
                ])->error("Checkout Confirmation Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //show custom error 500 page 
            return response(view('errors.500')); 
        }
    }
}
