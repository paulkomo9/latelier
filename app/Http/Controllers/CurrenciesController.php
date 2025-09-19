<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Throwable;
use App\Http\Services\StaticDataService;

class CurrenciesController extends Controller
{
    protected StaticDataService $staticdataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StaticDataService $staticdataService)
    {
        $this->middleware(['auth', 'check.access']);
        $this->staticdataService = $staticdataService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, string $id)
    {
        try {
                $criteria = [
                    'curr_code' => $id
                ];

                // get country data
                $country_data = $this->staticdataService->searchCountries($criteria , 'find');

                // check if response has error and its set to true
                if (isset($company_data['error']) && $company_data['error'] === true) {
                    // return error 
                     return response()->json([
                        'error' => $company_data['message']
                    ], 500);

                }
                
            return response()->json($country_data);

        } catch (Throwable $e) {
                // Custom logging to 'currencies-controller-error.log'
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/currencies-controller-error.log')
                ])->error("Edit Country Failed: " . $e->getMessage(), [
                    'route' => Route::currentRouteName(),
                    'currency_code' => $id,
                    'user_id' => Auth::id() ?? 'N/A',
                    'exception' => $e->getTraceAsString()
                ]);

            //return error 
            return response()->json([
                'error' => __('messages.country.search_failed')
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Search and display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, string $locale, string $code)
    {
        try {
            $user = Auth::user();
            $routeName = Route::currentRouteName() ?? throw new \RuntimeException("Current route name could not be determined.");

            session(['lock-expires-at' => now()->addMinutes($user->getLockoutTime())]);

            $searchname = $request->get('q');
            $cleanedCode = null;

            if (!empty($code) && strtolower($code) !== 'null') {
                $rawCodes = explode(',', $code);
                $cleaned = array_filter(array_map('trim', $rawCodes), fn($v) => $v !== '');

                $cleanedCode = count($cleaned) > 1 ? array_values($cleaned) : $cleaned[0] ?? null;
            }

            $criteria = [
                'code' => $cleanedCode,
                'searchname' => $searchname
            ];


            $countries = $this->staticdataService->searchCountries($criteria, 'get');

            if (isset($countries['error']) && $countries['error'] === true) {
                return response()->json([
                    'error' => $countries['message']
                ], 500);
            }

            return response()->json($countries);

        } catch (Throwable $e) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/currencies-controller-error.log')
            ])->error("Search Countries Failed: " . $e->getMessage(), [
                'route' => Route::currentRouteName(),
                'user_id' => Auth::id() ?? 'N/A',
                'request' => json_encode($request->all()),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => __('messages.country.search_failed')
            ], 500);
        }
    }
}
